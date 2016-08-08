<canvas width="250" height="250" id="canvas_picker" style="display:none; border:3px solid yellowgreen; border-radius: 10px;">
</canvas>
<script>
	var id = 0;
</script>

<div id="table-container">

	<form action="<?php echo PROJECT_DIRECTORY; ?>index.php?content=colorpicker&action=save&id=<?php echo $_GET['id']?>" method="post">

		<table id="table">
			<tbody id="haha">
            <?php

				$counter=1;
                while($row = $result->fetch_assoc()){
					$rgb=explode(',',$row['rgb']);
					var_dump($rgb);
					echo '<br>';
					echo '<br>';
					$r=$rgb[0];
					$g=$rgb[1];
					$b=$rgb[2];
					echo $r.' '.$g.' '.$b;
					echo '<br>';
					echo '<br>';

                    echo '<tr>
							<td>'.$counter.'</td>
                            <td><div class="col"><input class="hidden-row" autocomplete="off" name="hex[]" type="hidden" value="'.$row['hex'].'" readonly="readonly"></div></td>
                            <td><div><input type="time" step="1" name="time[]" autocomplete="off"  value="'.$row['time'].'">
											 <input type="hidden" name="id[]" value="'.$row['id'].'">
											 <input type="hidden" class="position" name="position[]" value="'.$counter.'"></div></td>
                            <td><div class="col"><input class="red" name="rgb[]" autocomplete="off" type="text" value="'.$r.'"></div></td>
                            <td><div class="col"><input class="green" name="rgb[]" autocomplete="off" type="text" value="'.$g.'"></div></td>
                            <td><div class="col"><input class="blue" name="rgb[]" autocomplete="off" type="text" value="'.$b.'"></div></td>
                            <td><div class="color" style="background-color:rgb( ';
					if($row['rgb']!==''){
						echo $row['rgb'].')';
					}
					else{
						echo '#FFFFFF';
					}
					echo '"></div></td>';

                    if(isset($row['rgb']) && isset($row['hex'])){
                        echo '<td><button class="button delete-row" type="button"">
                       	'.'Delete'.'</button></td></tr>';
                    }
                    else{
                        echo '</tr>';
                    }
					$counter++;
                }

            ?>
			</tbody>
		</table>
		<script>
			$("#table tbody").sortable({
				update: function(event, ui) {
					$('#table tr').each(function() {
						$(this).children('td:first-child').html(+$(this).index()+1);
						$(this).children().find('.position').val(+$(this).index()+1);
					});
				}
			});
			var x=document.getElementById("haha");

			function addRow(){

				if(x.innerText.length){
					id=+x.lastElementChild.innerText[0]+1;
				}
				else{
					id=1;
				}
				var position = +$('#haha').find('.position').last().val()+1;
				$('#haha').append('<tr>' +
					'<td>'+id+'</td>' +
					'<td><div class="col"><input class="hidden-row" autocomplete="off" name="hex[]" type="hidden" readonly="readonly"/></div></td>' +
					'<td><div><input type="time" step="1" name="time[]">'	+
					'<input class="val" type="hidden" name="id[]" value="-1">' +
					'<input class="position" name="position[]" type="hidden" value="'+position+'"></div></td>' +
					'<td><div class="col"><input class="red" name="rgb[]" autocomplete="off" type="text"></div></td>' +
					'<td><div class="col"><input class="green" name="rgb[]" autocomplete="off" type="text"></div></td>' +
					'<td><div class="col"><input class="blue" name="rgb[]" autocomplete="off" type="text"></div></td>' +
					'<td><div class="color" style="background-color:white;"></div></td>' +
					'<td><button class="button delete-row" type="button">Delete</button>' +
					'</td></tr>');
			}
			$('table').on('click','.delete-row',function(){
				if($(this).parent().parent().find(".val").length){
					$(this).parent().parent().remove();
				}
				else{
					$(this).parent().parent().find('.hidden-row').val('0');
					$(this).parent().parent().hide();
				}
			});
			$(function(){
				$('button').on('submit',function(){
					if(validate('haha')===false){
						alert('haha');
					}
				});
			});
			$('.red').on('change',function(){
				$red=$(this).val();
				$green=$(this).parent().parent().parent().find('.green').val();
				$blue=$(this).parent().parent().parent().find('.blue').val();
				$a=$(this).parent().parent().find('.color');

				$(this).parent().parent().parent().find('.color').css("background-color", "rgb("+ $red+"," + $green + "," + $blue + ")");
			});
			$('.green').on('change',function(){
				$green=$(this).val();
				$red=$(this).parent().parent().parent().find('.red').val();
				$blue=$(this).parent().parent().parent().find('.blue').val();
				$a=$(this).parent().parent().find('.color');
				$(this).parent().parent().parent().find('.color').css("background-color", "rgb("+ $red+"," + $green + "," + $blue + ")");
			});
			$('.blue').on('change',function(){
				$blue=$(this).val();
				$red=$(this).parent().parent().parent().find('.red').val();
				$green=$(this).parent().parent().parent().find('.green').val();
				$a=$(this).parent().parent().find('.color');
				$(this).parent().parent().parent().find('.color').css("background-color", "rgb("+ $red+"," + $green + "," + $blue + ")");
			});
		</script>
		<div id="save-colorpicker">
			<button class="button" type="button" onclick="addRow()">Add another colour!</button>
			<button class="button submit-button" type="submit" onsubmit="validate()">Save</button>
		</div>
	</form>
</div>
<script>
	var submit=$('.submit-button');
	submit.on('click',function(){
		if(!validate('#haha')){
			alert('Seconds should be number!');
		}
	});

	var inputArray = new Array();
	var RInput=null;
	var GInput=null;
	var BInput=null;
	var currentRow = null;
	$('table').on("click","tr",function(){
		inputArray = $(this).find('input');
		RInput=$(this).find('input:eq(4)');
		GInput=$(this).find('input:eq(5)');
		BInput=$(this).find('input:eq(6)');
		currentRow = $(this).find('.color');
	});
	var canvas = document.getElementById('canvas_picker').getContext('2d');
	var img= new Image();
	img.src="images/color-picker.png";
	$(img).load(function(){
		canvas.drawImage(img,0,0);
	});
	$('#canvas_picker').click(function(event){
		var x=event.pageX - this.offsetLeft;
		var y=event.pageY - this.offsetTop;
		var imgData = canvas.getImageData(x,y,1,1).data;
		var R=imgData[0];
		var G=imgData[1];
		var B=imgData[2];

		RInput.val(R);
		GInput.val(G);
		BInput.val(B);

		var rgb=R + ',' + G + ',' + B;

		function rgbToHex(R,G,B) {return toHex(R)+toHex(G)+toHex(B)}
		function toHex(n) {
			n = parseInt(n,10);
			if (isNaN(n)) return "00";
			n = Math.max(0,Math.min(n,255));return "0123456789ABCDEF".charAt((n-n%16)/16) + "0123456789ABCDEF".charAt(n%16);
		}
		var hex = rgbToHex(R,G,B);
		$(currentRow[0]).css("background-color", "rgb("+ RInput.val()+"," + GInput.val() + "," + BInput.val() + ")");
	});
	$('table').on("hover",function(){
		$('tr .color').mousedown(function(){
			var pos = $(this).position();
			//var width=$('input').outerWidth();

			$("#canvas_picker").css({
				position: "absolute",
				top: pos.top + 65 + "px",
				left: (pos.left)  + "px"
			}).toggle();
		});
	});

</script>
