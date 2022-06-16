<?php

// Register for a Rebrickable account and palce your API key here
// https://rebrickable.com/api/

$key = '';

// The CSV files have been imported into the database
// THe API will be used for additional data

$connect = mysqli_connect(
    'localhost',
    'root',
    'root',
    'lego'
);

function list_themes($parent_id = '')
{

    global $connect;

    $query = 'SELECT themes.id,
        themes.name, (
            SELECT COUNT(*)
            FROM sets
            WHERE sets.theme_id = themes.id
        ) AS sets, (
            SELECT COUNT(*)
            FROM themes AS subthemes
            WHERE subthemes.parent_id = themes.id
        ) AS themes
        FROM themes
        WHERE themes.parent_id = "'.$parent_id.'"
        ORDER BY themes.name';
    $result = mysqli_query($connect, $query);

    echo '<ul>';

    while($record = mysqli_fetch_assoc($result))
    {
        echo '<li>';

        echo '<a href="?theme='.$record['id'].'">'.$record['name'].'</a>';

        echo '<br>
            Themes: '.$record['themes'];

        if($record['themes'])
        {
            list_themes($record['id']);
        }

        echo '<br>
            Sets: '.$record['sets'];

        if($record['sets'])
        {
            list_sets($record['id']);
        }

        echo '</li>';
    }

    echo '</ul>';

}

function list_sets($theme_id)
{

    global $connect;

    $query = 'SELECT sets.set_num,
        sets.name,
        sets.year,
        sets.num_parts, (
            SELECT COUNT(*)
            FROM inventories
            WHERE inventories.set_num = sets.set_num
        ) AS inventories, (
            SELECT COUNT(*)
            FROM inventory_sets
            WHERE inventory_sets.set_num = sets.set_num
        ) AS inventory_sets
        FROM sets
        WHERE sets.theme_id = '.$theme_id.'
        ORDER BY sets.year, sets.name
        LIMIT 5';
    $result = mysqli_query($connect, $query);

    echo '<ul>';

    while($record = mysqli_fetch_assoc($result))
    {
        echo '<li>';

        echo '<a href="?set='.$record['set_num'].'">'.$record['name'].'</a>';
        echo '<br>';
        echo 'Parts: '.$record['num_parts'];
        echo '<br>';
        echo 'Year: '.$record['year'];
        echo '<br>';

        echo 'Inventories: '.$record['inventories'];

        if($record['inventories'])
        {
            list_inventories($record['set_num']);
        }
        else echo '<br>';

        
        echo 'Inventory Sets: '.$record['inventory_sets'];

        if($record['inventory_sets'])
        {
            list_inventory_sets($record['set_num']);
        }

        echo '</li>';
    }

    echo '</ul>';

}

function list_inventories($set_num)
{

    global $connect;

    $query = 'SELECT inventories.set_num,
        inventories.version
        FROM inventories
        WHERE inventories.set_num = "'.$set_num.'"
        ORDER BY inventories.version';
    $result = mysqli_query($connect, $query);

    echo '<ul>';

    while($record = mysqli_fetch_assoc($result))
    {
        echo '<li>';

        echo 'Set: '.$record['set_num'].'</a>';
        echo '<br>';
        echo 'Version: '.$record['version'].'</a>';

        echo '</li>';
    }

    echo '</ul>';

}

function list_inventory_sets($set_num)
{

    global $connect;

    $query = 'SELECT sets.set_num,
        sets.name,
        inventories.version,
        inventory_sets.quantity
        FROM inventory_sets
        LEFT JOIN inventories 
        ON inventories.id = inventory_sets.inventory_id
        LEFT JOIN sets 
        ON inventories.set_num = sets.set_num
        WHERE inventory_sets.set_num = "'.$set_num.'"
        -- ORDER BY inventory_sets.inventory_id';
    $result = mysqli_query($connect, $query);

    echo '<ul>';

    while($record = mysqli_fetch_assoc($result))
    {
        echo '<li>';

        echo 'Set: '.$record['set_num'].'</a>';
        echo '<br>';
        echo 'Name: '.$record['name'].'</a>';
        echo '<br>';
        echo 'Veraion: '.$record['version'].'</a>';
        echo '<br>';
        echo 'Quantity: '.$record['quantity'].'</a>';

        echo '</li>';
    }

    echo '</ul>';

}


list_themes();
