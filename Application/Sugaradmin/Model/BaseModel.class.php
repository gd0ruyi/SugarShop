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
        'options' => array(),
        'data' => array(),
        'count' => 0,
        'pager' => array()
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
     * 通用获取分页列表信息
     *
     * @param array $options 查询条件参数
     * @param integer $listRows 每页条数,默认为0，当为0时表示不分页
     * @param integer $nowPage 当前页,默认使用ThinkPHP的P
     * 
     * @return array() $pager 分页信息结果集
     */
    public function getList($options, $listRows = 0, $nowPage = 0)
    {
        // 初始化
        if (empty($options)) {
            $options = array();
            $options['where'] = array();
            $options['order'] = array();
        }
        $this->bsm_rs = array();
        $this->bsm_rs['pk'] = $this->getPk();
        $this->bsm_rs['options'] = $options;
        $this->bsm_rs['data'] = array();
        $this->bsm_rs['count'] = 0;
        $this->bsm_rs['pager'] = array();
        $this->bsm_rs['status'] = 0;
        $this->bsm_rs['msg'] = "success";

        //校验
        //默认为使用主键倒序排序
        $options['order'] = empty($options['order']) ? array($this->bsm_rs['pk'] => 'desc') : $options['order'];
        // 强制格式化排序，将会过滤处理
        $options['order'] = $this->_formatOptionsSort($options['order']);
        $nowPage = intval($nowPage);
        $listRows = intval($listRows);

        // 求条件后的条数
        $this->bsm_rs['count'] = $this->where($options['where'])->order($options['order'])->count();

        // 处理后的重新赋值
        $this->bsm_rs['options'] = $options;

        // 判断是否进行分页处理
        if ($listRows) {
            $pager = new Page($this->bsm_rs['count'], $listRows);
            $this->bsm_rs['pager'] = $pager->getInfo($nowPage);
            $nowPage = $this->bsm_rs['pager']['nowPage'];
            // 查询结果集
            $this->bsm_rs['data'] = $this->page($nowPage, $listRows)->select($options);
        } else {
            $this->bsm_rs['data'] =  $this->select($options);
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
    private function _parseResValue($data)
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
