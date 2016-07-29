<div>
    <table style="color:white; width:700px; margin:auto;">
        <thead>

        </thead>
        <tbody>


<?php
    while($row = $result -> fetch_assoc()) {
        echo '<tr>
                <input class="id" type="hidden" value="'.$row["id"].'">
                <td style="color:black; border-radius: 5px; width:160px; text-align:center; background-color: #EFEFEF;">' . $row['name'] . '</td>
                <td><a href="http://localhost/chinatown/index.php?content=colorpicker&action=showAll&id=' . $row['id'] . '" class="button">Set Colours</a></td>'.
                '<td><input style="text-align:center; font-size:14px; background-color:#EFEFEF; width:100px; height:50px" readonly="readonly" value=';
                if(!(is_numeric($row['group_number']))){
                    echo 'Notselected';
                }
                else{
                    echo '"Group '.$row['group_number'].'"';
                }
                echo '></td>
                <td><a href="http://localhost/chinatown/index.php?content=schedule&action=deleteRow&deletedRow=' . $row['id'] . '" class="button">Delete schedule</a></td>
                <td><button class="button edit-button" type="button">Edit</button></td>
                <td><a href="http://localhost/chinatown/index.php?content=schedule&action=copy&copiedid='.$row['id'].'" class="button" type="button">Copy</button></td>
                </tr>';

    }
?>

        </tbody>
    </table>
</div>
<div style="width:500px; margin:auto; padding-top:200px">
    <form action="http://localhost/chinatown/index.php?content=schedule&action=add" method="post">
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
            <button style="" type="submit" class="button">Add schedule</button>
        </div>
    </form>
</div>
<script>

    $('.edit-button').one('click',function(){
        var options=$('');
        options+='<option selected="selected">'+'None'+'</option>';
        for(i=1;i<=32;i+=1){
            options+='<option value="'+ i +'" >' + i + '</option>';
        }
        var id=$(this).parent().parent().find('.id').val();
        var $element=$('<form class="save-form" action="http://localhost/chinatown/index.php?content=schedule&action=save" method="post"><input name="id" type="hidden" value='+id+'><tr>' +
            '<td><input style="display:block; width:140px" placeholder="New name" name="new-name" type="text"></td>' +
            '<td><select style="width:165px" name="new-group">' + options +
            '</select></td>' +
            '<td><button style="width:82px;" class="button" type="submit">Save</button>' +
                '<button style="width:82px;" class="cancel-button button" type="button">Cancel</button></td>' +
            '</tr></form>');
        $element.insertAfter($(this).parent().parent());
    });
    $('.edit-button').on('click',function(){
        $(this).parent().parent().next().toggle();
    });
    $('table').on('click','.cancel-button',function(){
        $(this).parent().hide();
    });
</script>