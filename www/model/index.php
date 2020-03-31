<?php

function sort_items($db, $str)
{
    $sql = "SELECT
                `name`,
                `image`,
                price,
                stock,
                item_id
            FROM
                items
            ORDER BY
                CASE ?
                    WHEN '' THEN created
                END DESC,
                CASE ?
                    WHEN 'new' THEN created
                END DESC,
                CASE ?
                    WHEN 'high' THEN price
                END DESC,
                CASE ?
                    WHEN 'low' THEN price
                END ASC;";
    return fetch_all_query($db, $sql, array($str, $str, $str, $str));
}
