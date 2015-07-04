<?php


class OPanda_EmailLocker_Channels_StatsTable extends OPanda_StatsTable {
    
    public function getColumns() {
        
        return array(
            'index' => array(
                'title' => ''
            ),
            'title' => array(
                'title' => __('Post Title', 'emaillocker')
            ),
            'unlock' => array(
                'title' => __('Number of Unlocks', 'emaillocker'),
                'hint' => __('The number of unlocks made by visitors.', 'emaillocker'),
                'highlight' => true,
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-google' => array(
                'title' => __('Via Google'),
                'cssClass' => 'opanda-col-number'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
}

class OPanda_EmailLocker_Channels_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'unlock-via-form' => array(
                'title' => __('Via Opt-In Form'),
                'color' => '#31ccab'
            ),
            'unlock-via-facebook' => array(
                'title' => __('Via Facebook'),
                'color' => '#7089be'
            ),
            'unlock-via-google' => array(
                'title' => __('Via Google'),
                'color' => '#e26f61'
            ),
            'unlock-via-linkedin' => array(
                'title' => __('Via LinkedIn'),
                'color' => '#006080'
            ) 
        );
    }
}