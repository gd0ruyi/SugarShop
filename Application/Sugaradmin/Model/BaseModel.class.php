<?php

namespace Sugaradmin\Model;

use Think\Model\MongoModel;
use Think\Page;

/**
 * 基础model,用于通用model继承
 * 
 * @author gd0ruyi@163.com 2019-1-16
 *        
 */
class BaseModel extends MongoModel
{
    /**
     * 基础model结果对象，用于存储结果返回的查询。
     *
     * @var array
     */
    public $bsm_rs = array(
        'pk' => '',
        'query' => array(),
        'data' => array(),
        'count' => 0,
        'pager' => array(),
        // 用于判断处理错误
        'status' => 0,
        'msg' => 'success'
    );


    /**
     * 初始化，用于父类的初始化实现
     *
     * @param string $name        	
     * @param string $tablePrefix        	
     * @param string $connection        	
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }

    /**
     * 获取单条信息
     *
     * @param array $query 查询条件参数
     * @return void
     */
    public function getOne($query)
    {
        // 使用getList处理
        $this->bsm_rs = $this->getList($query, 0, 0);
        // 判断数据是否存在多条的情况，存在则表示查询异常
        $count = count($this->bsm_rs['data']);
        if ($count > 1) {
            $this->bsm_rs['status'] = 1;
            $this->bsm_rs['msg'] = "warring: getOne (res count={$count}) > 1";
        }
        // 取出单条赋值
        $this->bsm_rs['data'] = array_shift($this->bsm_rs['data']);

        return $this->bsm_rs;
    }

    /**
     * 通用获取分页列表信息
     *
     * @param array $query 查询条件参数
     * @param integer $listRows 每页条数,默认为0，当为0时表示不分页
     * @param integer $nowPage 当前页,默认使用ThinkPHP的P
     * 
     * @return array() $pager 分页信息结果集
     */
    public function getList($query, $listRows = 0, $nowPage = 0)
    {
        // 初始化
        if (empty($query)) {
            $query = array();
            $query['where'] = array();
            $query['order'] = array();
        }
        $this->bsm_rs = array();
        $this->bsm_rs['pk'] = $this->getPk();
        $this->bsm_rs['options'] = $query;
        $this->bsm_rs['data'] = array();
        $this->bsm_rs['count'] = 0;
        $this->bsm_rs['pager'] = array();
        $this->bsm_rs['status'] = 0;
        $this->bsm_rs['msg'] = "success";

        //校验
        //默认为使用主键倒序排序
        $query['order'] = empty($query['order']) ? array($this->bsm_rs['pk'] => 'desc') : $query['order'];
        // 强制格式化排序，将会过滤处理
        $query['order'] = $this->_formatOptionsSort($query['order']);
        $nowPage = intval($nowPage);
        $listRows = intval($listRows);

        // 求条件后的条数
        $this->bsm_rs['count'] = $this->where($query['where'])->order($query['order'])->count();

        // 处理后的重新赋值
        $this->bsm_rs['query'] = $query;

        // 判断是否进行分页处理
        if ($listRows) {
            $pager = new Page($this->bsm_rs['count'], $listRows);
            $this->bsm_rs['pager'] = $pager->getInfo($nowPage);
            $nowPage = $this->bsm_rs['pager']['nowPage'];
            // 查询结果集
            $this->bsm_rs['data'] = $this->page($nowPage, $listRows)->select($query);
        } else {
            $this->bsm_rs['data'] =  $this->select($query);
        }

        // 自定义结果集格式化
        $this->bsm_rs['data'] = $this->_parseResValue($this->bsm_rs['data']);
        return $this->bsm_rs;
    }

    /**
     * 排序字段处理
     *
     * @param array|string $sort 排序传入的字符或数组
     * @return array $sort
     */
    private function _formatOptionsSort($sort)
    {
        // 如果是字符串强行打散为数组
        if (is_string($sort)) {
            $sort_arr = explode(',', $sort);
            $sort = array();
            foreach ($sort_arr as $k => $v) {
                $v = explode(' ', merge_spaces($v));
                $sort[$v[0]] = isset($v[1]) && strtolower($v[1]) == 'asc' ? 'asc' : 'desc';
            }
        }

        // 数组处理
        if (is_array($sort)) {
            foreach ($sort as $k => $v) {
                if (is_int($k)) {
                    // 字段过滤
                    if (isset($this->fields) && !isset($this->fields[$v])) {
                        unset($sort[$v]);
                    }
                    // 默认处理为asc
                    $sort[$v] = 'asc';
                } else {
                    // 字段过滤
                    if (isset($this->fields) && !isset($this->fields[$k])) {
                        unset($sort[$k]);
                    }
                    $sort[$k] = strtolower($v) == 'asc' ? 'asc' : 'desc';
                }
            }
        }

        return $sort;
    }

    /**
     * 返回结果集格式化
     *
     * @param array $data
     * @return $data array()
     */
    protected function _parseResValue($data)
    {
        if (empty($data) || !is_array($data)) {
            return $data;
        }

        // 合并映射配置，用于子类配置覆盖
        $bsm_output_m = C('BASE_MODEL_MAP');
        $m_output_cn = strtoupper(CONTROLLER_NAME . '_MODEL_MAP');
        $m_output_m = C($m_output_cn);
        $m_output_m = empty($m_output_m) ? array() : $m_output_m;
        $map_change = array_merge($bsm_output_m, $m_output_m);

        // 时间配置处理
        $date_format_c = C('DATE_FORMAT');

        // 遍历行
        foreach ($data as $index => $row) {
            // 遍历列
            foreach ($row as $rk => $rv) {
                // 遍历映射判断
                foreach ($map_change as $mk => $mv) {
                    // 存在对应的键名进行处理
                    if (substr_count($rk, $mk) > 0) {
                        // 生成新的键名
                        $rk = $rk . $mv['output_key_suff'];
                        // 不同输出类型处理
                        switch ($mv['output_type']) {
                            case 'DATE_FORMAT':
                                // 判断使用为DATE的默认配置还是使用output_value配置
                                $data_format = $date_format_c[$mv['output_value']];
                                $data_format = $data_format ? $data_format : $mv['output_value'];
                                $row[$rk] = date($data_format, $rv);
                                break;
                            case 'MAP':
                                $row[$rk] = $mv['output_value'][$rv];
                                break;
                            default:
                                $row[$rk] = $rv;
                                break;
                        }
                    }
                }
            }
            $data[$index] = $row;
        }
        return $data;
    }
}
