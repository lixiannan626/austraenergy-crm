<?php
//List
function show_item_list($items, $thds)
{
    //Stat

    //Table
    if(count($items) == 0)
        echo "<p>No item found</p>";
    else
    {
        echo "<table>";
        echo "<tr>";
        foreach($thds as $th=>$td)
            echo "<th>$th</th>";
        echo "
            <th>Actions</th>
        </tr>";

        $n = 0;
        foreach($items as $item)
        {
            show_item_row($item, $thds, ($n++));
        }


        echo "</table>";
    }
}

function show_item_row($item, $thds, $n = 0)
{
    if($item == NULL)
        return "";

    echo "<tr data-id='".$item['item_id']."' data-cat='".$item['item_category']."' class='row".(((int)($n/3))%2)."'>";
    foreach($thds as $th=>$td)
    echo "<td>$item[$td]</td>";
    //Controls, Available, Edit, Delete
    echo "<td>
                    <a class='SmallControl ItemRetrieve ItemAjax' data-crud='retrieve' data-id='".$item['item_id']."'>Edit</a>
                    <a class='SmallControl ItemDelete ItemAjax' data-crud='delete' data-id='".$item['item_id']."'>Delete</a>
                    ";
    if($item['item_available'] == 0)
        echo "<a class='SmallControl ItemAvailable ItemAjax' data-crud='update_ava' data-id='".$item['item_id']."'>Make Available</a>";
    else
        echo "<a class='SmallControl ItemUnavailable ItemAjax' data-crud='update_ava' data-id='".$item['item_id']."'>Make Unavailable</a>";

    echo "
                  </td>";

    echo "</tr>";
}
?>