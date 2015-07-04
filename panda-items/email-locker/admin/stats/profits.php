<?php


class OPanda_EmailLocker_Profits_StatsTable extends OPanda_StatsTable {
    
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
            'emails' => array(
                'title' => __('Emails Collected', 'emaillocker'),
                'hint' => __('The number of new emails added to the database. If the email exists in the database, this email will not be counted.', 'emaillocker'),
                'cssClass' => 'opanda-col-number'
            )
        );
    }
    
    public function columnEmails( $row ) {
        if ( !isset( $row['email-received'] ) ) $row['email-received'] = 0;
        if ( !isset( $row['email-confirmed'] ) ) $row['email-confirmed'] = 0;
        
        if ( $row['email-received'] > 0 ) echo '+';
        echo $row['email-received'];
        
        if ( BizPanda::getSubscriptionServiceName() !== 'database' ) {
            echo ' <em>(' . $row['email-confirmed'] . ' confirmed)';
        }
    }
}

class OPanda_EmailLocker_Profits_StatsChart extends OPanda_StatsChart {
    
    public $type = 'line';
    
    public function getFields() {
        
        return array(
            'aggregate_date' => array(
                'title' => __('Date')
            ),
            'email-received' => array(
                'title' => __('Emails Collected', 'emaillocker'),
                'color' => '#FFCC66'
            ),
            'email-confirmed' => array(
                'title' => __('Subscriptions Confirmed', 'emaillocker'),
                'color' => '#336699'
            ),
        );
    }
}