<div>
    <table id="schedule">
        <thead class="ui-widget-header">
        <th align="center">Name</th>
        <th align="center">Group Number</th>
        <th align="center">Date</th>
        <th colspan="4"></th>
        </thead>
        <tbody>
        <?php
        while($row = $result -> fetch_assoc()) {
            echo '<tr class="line">
                <input class="id" type="hidden" value="'.$row["id"].'">
                <td><div>' . $row['name'] . '</div></td>'.
                '<td><div><input  class="row-group" readonly="readonly" value=';
            if(!(is_numeric($row['group_number']))){
                echo 'Notselected';
            }
            else{
                echo '"Group '.$row['group_number'].'"';
            }
            echo '></div></td>
                <td><input class="td-date" type="text" value="'.$row['date'].'" readonly="readonly"><label>Every day<input name="every_day" type="checkbox" readonly="readonly" disabled="disabled"';
            if($row['every_day']==1){
                echo 'checked="checked"/></label></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=colorpicker&action=showAll&id=' . $row['id'] . '" class="button">Set Colours</a></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=schedule&action=deleteRow&deletedRow=' . $row['id'] . '" class="button confirmation">Delete schedule</a></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=schedule&action=copy&copiedid='.$row['id'].'" class="button" type="button">Copy</button></td>
                         <td><button class="button edit-button" type="button">Edit</button></td>
                        </tr>';
            }
            else{
                echo '/></label></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=colorpicker&action=showAll&id=' . $row['id'] . '" class="button">Set Colours</a></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=schedule&action=deleteRow&deletedRow=' . $row['id'] . '" class="button confirmation">Delete schedule</a></td>
                        <td><a href="'.PROJECT_DIRECTORY.'index.php?content=schedule&action=copy&copiedid='.$row['id'].'" class="button" type="button">Copy</button></td>
                        <td><button class="button edit-button" type="button">Edit</button></td>
                        </tr>';
            }



        }
        ?>

        </tbody>
    </table>
</div>
<div id="add-schedule">
    <form action="<?php echo PROJECT_DIRECTORY; ?>index.php?content=schedule&action=add" method="post">
        <div>
            <input  name="name" type="text">
            <select name="group_number">
                <option selected="selected" style="display:none">Choose group</option>
                <option>None</option>
                <?php
                for ($i = 1; $i <= 32; $i += 1) {
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
                echo '</select>';
                ?>
                <input type="text" class="date" name="date" autocomplete="off">
                <label style="color:white;">Every day<input name="every-day" type="checkbox" /></label>
                <button style="" type="submit" class="button">Add schedule</button>
        </div>
    </form>
</div>
<script>

    $('.edit-button').one('click',function(){
        var options=$('');
        options+='<option selected="selected" style="display:none;">'+'New group'+'</option>';
        options+='<option>'+'None'+'</option>';
        for(i=1;i<=32;i+=1){
            options+='<option value="'+ i +'" >' + i + '</option>';
        }
        var id=$(this).parent().parent().find('.id').val();
        var $element=$('<tr style="display:none;" class="dropdown"><td colspan="6" style="padding-left:0px; "><form class="save-form" action="<?php echo PROJECT_DIRECTORY; ?>index.php?content=schedule&action=save" method="post"><input name="id" type="hidden" value='+id+'><table><tr>' +
            '<td><input class="new-name" placeholder="New name" name="new-name" type="text"></td>' +
            '<td><select style="width:165px" name="new-group">' + options +
            '</select></td>' +
            '<td><input type="text" name="date" class="date" autocomplete="off"><label style="color:white;">Every day<input name="every_day" type="checkbox" /></label></td></td>' +
            '<td><button style="width:82px;" class="button" type="submit">Save</button>' +
            '<button style="width:82px;" class="cancel-button button" type="button">Cancel</button></td>' +
            '</tr></table></form></td></tr>');
        $element.insertAfter($(this).parent().parent());
		$(this).parent().parent().next().find(".date").datepicker({ dateFormat: 'yy-mm-dd' });
    });
    $('.edit-button').on('click',function(){
        $(this).parent().parent().next().toggle();
        //var $temp = $(this).parent().parent().find('td:eq(0)');
    });

    $('table').on('click','.cancel-button',function(){
        $(this).parents().find('.dropdown').hide();
    });


    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete this schedule?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

</script>
