<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Store, Report, Shortcode};
use Mail;
use Carbon\Carbon;
use DB;
use App\Mail\DailyReport;

function escapeMD($text) {
    return str_replace(["-", ".", "_", "#", "?", "!", "|", "*"], ["\\-", "\\.", "\\_", "\\#", "\\?", "\\!", "\\|", "\\*"], $text);
}

class SendReportsController extends Controller
{
    public function send() {
        set_time_limit(0);
        $stores = Store::whereNotNull('report_id')->get();

        foreach($stores as $store)
            self::sendReportToStore($store, Carbon::now()->subDay()->startOfDay(), Carbon::now()->subDay()->endOfDay());
    }

    public static function sendReportToStore($store, $startDate, $endDate) {
        $report = $store->report;
        $queries = [];
        echo "Processing store " . $store->name . PHP_EOL;
        if(is_array($store->to_emails) && count($store->to_emails) > 0) {
            foreach($report->queries as $query) {
                echo "Processing query " . $query['subject'] . PHP_EOL;
                $table = self::generateQueryTable($store, $query, $startDate, $endDate);
                if($table === false) continue;
                echo "Table generated for query " . $query['subject'] . PHP_EOL;
                $queries[] = [
                    'preface' => $query['preface'],
                    'postface' => $query['postface'],
                    'table' => $table
                ];
            }

            if(count($queries) > 0) {
                $body = $report->preface . PHP_EOL . PHP_EOL . "---" . PHP_EOL . PHP_EOL;
                foreach($queries as $query)
                    $body .= (!empty($query['preface']) ? ($query['preface'] . PHP_EOL . PHP_EOL) : '') . $query['table'] . PHP_EOL . (!empty($query['postface']) ? ($query['postface'] . PHP_EOL . PHP_EOL) : '') . "---" . PHP_EOL . PHP_EOL;
                $body .= $report->postface;

                // replace special codes and shortcodes
                Carbon::setLocale('en');
                $subject = str_replace(['%DATE', '%STORE', '%SELLER'], [
                    Carbon::now()->subDay()->format('d M Y'),
                    $store->name,
                    $store->seller->nickname
                ], $report->subject);
                $body = str_replace(['%DATE', '%STORE', '%SELLER'], [
                    Carbon::now()->subDay()->format('d M Y'),
                    $store->name,
                    $store->seller->nickname
                ], $body);

                foreach(Shortcode::all() as $sc)
                    $body = preg_replace('/#'.preg_quote($sc->key).'(?![a-zA-Z0-9\-])/m', $sc->value, $body);

                try {
                    echo "Sending emails to ".implode(", ", $store->to_emails) . PHP_EOL;
                    if(is_array($store->bcc_emails) && count($store->bcc_emails) > 0) {
                        echo "Sending BCC emails to ".implode(", ", $store->bcc_emails) . PHP_EOL;
                        Mail::to($store->to_emails)->bcc($store->bcc_emails)->send(new DailyReport($subject, $body));
                    } else
                        Mail::to($store->to_emails)->send(new DailyReport($subject, $body));
                } catch(\Swift_TransportException $e) {
                    try {
                        if(is_array($store->bcc_emails) && count($store->bcc_emails) > 0)
                            Mail::to($store->to_emails)->bcc($store->bcc_emails)->send(new DailyReport($subject, $body));
                        else
                            Mail::to($store->to_emails)->send(new DailyReport($subject, $body));
                    } catch(\Swift_TransportException $e) {
                        try {
                            if(is_array($store->bcc_emails) && count($store->bcc_emails) > 0)
                                Mail::to($store->to_emails)->bcc($store->bcc_emails)->send(new DailyReport($subject, $body));
                            else
                                Mail::to($store->to_emails)->send(new DailyReport($subject, $body));
                        } catch(\Swift_TransportException $e) {
            
                        }
                    }
                }
            }
        }
    }

    protected static function generateQueryTable($store, $query, $startDate, $endDate) {
        $onlyCurrent = filter_var($query['onlyCurrent'], FILTER_VALIDATE_BOOLEAN);

        if($onlyCurrent)
          $statuses = DB::table('test_unit');
        else
          $statuses = DB::table('test_unit_status')
            ->leftJoin('test_unit', 'test_unit_status.test_unit_id', '=', 'test_unit.id');

        $statuses = $statuses->leftJoin('test_order', 'test_order.id', '=', 'test_unit.test_order_id')
          ->leftJoin('store', 'test_order.store_id', '=', 'store.id')
          ->leftJoin('tester', 'test_unit.tester_id', '=', 'tester.id')
          ->leftJoin('product', 'test_order.product_id', '=', 'product.id')
          ->where('test_unit'.($query['statusType'] == "expiring" ? '.expires_on' : ($onlyCurrent ? '.updated_at' : '_status.created_at')), '>', $startDate)
          ->where('test_unit'.($query['statusType'] == "expiring" ? '.expires_on' : ($onlyCurrent ? '.updated_at' : '_status.created_at')), '<', $endDate)
          ->where('store.id', $store->id);

        if($query['statusType'] == "others" && is_array($query['statuses']) && count($query['statuses']))
          $statuses = $statuses->whereIn('test_unit' . (!$onlyCurrent ? '_status' : '') . '.status', $query['statuses']);
        elseif($query['statusType'] == "expiring")
          $statuses = $statuses->where('test_unit.status', '0');

        $mappings = [
            "amazon-order-no" => ['column' => 'test_unit.amazon_order_id', 'alias' => 'amazon_order_id'],
            "asin" => ['column' => 'product.ASIN', 'alias' => 'asin'],
            "paypal-account" => ['column' => 'test_unit.paypal_account', 'alias' => 'paypal_account'],
            "refunded" => ['column' => 'test_unit.refunded', 'alias' => 'refunded'],
            "refunded-amount" => ['column' => 'test_unit.refunded_amount', 'alias' => 'refunded_amount'],
            "review-url" => ['column' => 'test_unit.review_url', 'alias' => 'review_url'],
            "state" => ['column' => 'test_unit' . (!$onlyCurrent ? '_status' : '') . '.status', 'alias' => 'status'],
            "tester-name" => ['column' => 'tester.name', 'alias' => 'tester_name']
        ];

        if(isset($query['orderBy'])
          && is_array($query['orderBy'])
          && isset($query['orderBy']['field'])
          && in_array($query['orderBy']['field'], array_keys($mappings))
        ) {
            $order = isset($query['orderBy']['type']) && $query['orderBy']['type'] == "desc" ? "desc" : "asc";
            $statuses = $statuses->orderBy($mappings[$query['orderBy']['field']]['column'], $order);
        } else
            $statuses = $statuses->orderBy('test_unit.created_at');
        
        $selection = [];
        $fields = $query['fields'];
        if(is_array($fields)) {
            foreach($fields as $field)
                if(in_array($field, array_keys($mappings)))
                    $selection[] = $mappings[$field]['column'] . " as " . $mappings[$field]['alias'];
        }

        if(count($selection)) {
            $statuses = $statuses->select($selection)->get();

            if($statuses->count() > 0) {
                echo "Found " . $statuses->count() . " results from query" . PHP_EOL;
                $names = config('testUnit.reportFields');
                $namedFields = array_map(function($field) use($names) {
                    return $names[$field];
                }, $fields);
                $output = "|" . implode("|",$namedFields) . "|" . PHP_EOL;
                for($i = 0; $i < count($fields); $i++) $output .= "|-";
                $output .= "|" . PHP_EOL;
                foreach($statuses as $entry) {
                    foreach($fields as $field) {
                        if($field == "state")
                            $output .= "|" . config('testUnit.englishStatuses')[$entry->{$mappings[$field]['alias']}];
                        else
                            $output .= "|" . escapeMD($entry->{$mappings[$field]['alias']});
                    }
                    $output .= "|" . PHP_EOL;
                }
                return $output;
            }
        }
        
        return false;
    }
}
