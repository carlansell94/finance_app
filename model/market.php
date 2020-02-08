<?php

namespace Finance\model;

use Finance\core\Connection;

final class Market
{
    public function __construct()
    {
        $this->conn = Connection::instance();
    }

    public function isOpen(): bool
    {
        $query = "SELECT
                      timezone,
                      open,
                      close
                  FROM stock_markets
                  JOIN stock_exchanges USING (exchange_id)
                  WHERE market_id = ?";

        $values[] = $this->getId();

        $stmt = $this->conn->runQuery($query, $values);
        $stmt->bind_result($timezone, $open, $close);
        $stmt->fetch();

        $tz_open = new \DateTime($open, new \DateTimeZone($timezone));
        $tz_close = new \DateTime($close, new \DateTimeZone($timezone));
        $now = new \DateTime();

        if ($now >= $tz_open && $now <= $tz_close && $now->format('N') < 6) {
            return true;
        }

        return false;
    }

    public static function getSyncList(): array
    {
        $conn = Connection::instance();

        $query = "SELECT
                      market_id,
                      market_name,
                      market_symbol,
                      timezone,
                      open,
                      close,
                      date
                  FROM stock_markets
                  JOIN stock_exchanges USING (exchange_id)
                  LEFT JOIN (
                      SELECT
                          market_id,
                          MAX(date) + INTERVAL 1 DAY AS date
                      FROM stock_market_prices
                      GROUP BY market_id
                  ) t1 USING (market_id)
                  LEFT JOIN stock_market_prices USING (market_id, date)";

        $output = $conn->runQuery($query);
        $list = $output->get_result();
        $result = array();

        while ($currency = $list->fetch_object("\Finance\core\Market")) {
            $result[$currency->market_id] = $currency;
        }

        return $result;
    }
}

