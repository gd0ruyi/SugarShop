<?php
/**
 * 输出时使用的映射信息表
 */
return array(
    // 时间格式化配置
    'DATE_FORMAT' => array(
        'TIME' => 'Y-m-d H:i:s',
        'DAY' => 'Y-m-d'
    ),

    // 基础输出映射
    'BASE_MODEL_MAP' => array(
        // 时间转换
        '_time' => array(
            'output_type' => 'DATE_FORMAT',
            'output_value' => 'TIME',
            // 'output_value' => 'Y-m-d H:i:s',
            'output_key_suff' => '_format'
        ),
        'status' => array(
            'output_type' => 'MAP',
            'output_value' => array(
                0 => '启用',
                1 => '禁用'
            ),
            'output_key_suff' => '_name'
        ),
        'use_type' => array(
            'output_type' => 'MAP',
            'output_value' => array(
                0 => '管理员',
                1 => '普通用户'
            ),
            'output_key_suff' => '_name'
        )
    ),

    // 基础输出映射
    // 'TEST_MODEL_MAP' => array(
    //     'status' => array(
    //         'output_type' => 'MAP',
    //         'output_value' => array(
    //             0 => '启用A',
    //             1 => '禁用B'
    //         ),
    //         'output_key_suff' => '_name'
    //     )
    // )
);
