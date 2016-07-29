<style>
	canvas:hover{
		cursor: crosshair;
	}
	tr{
		border:1px solid white !important;
	}
</style>
<canvas width="250" height="250" id="canvas_picker" style="display:none; border:3px solid yellowgreen; border-radius: 10px;">
</canvas>
<script>
	var id = 0;
</script>

<div style="width:1000px; padding-left:22%;">

	<form action="http://localhost/chinatown/index.php?content=colorpicker&action=save&id=<?php echo $_GET['id']?>" method="post">

		<table id="table" style="color:white;">
			<tbody id="haha">
            <?php
				$counter=1;
                while($row = $result->fetch_assoc()){

                    echo '<tr>
							<td>'.$counter.'</td>
                            <td><div class="col" style="position:relative;">HEX: <input class="hidden-row" autocomplete="off" name="hex[]" type="text" value="'.$row['hex'].'" readonly="readonly"></div></td>
                            <td><div>Seconds:<input class="int" type="text" name="time[]" autocomplete="off"  value="'.$row['time'].'">
											 <input type="hidden" name="id[]" value="'.$row['id'].'">
											 <input type="hidden" class="position" name="position[]" value="'.$counter.'"></div></td>
                            <td><div class="col">RGB: <input name="rgb[]" autocomplete="off" type="text" value="'.$row['rgb'].'" readonly="readonly"></div></td>
                            <td><div class="color" style="width:65px; height=65px; background-color: ';
					if($row['hex']!==''){
						echo $row['hex'];
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
					'<td><div class="col">HEX: <input class="hidden-row" autocomplete="off" name="hex[]" type="text" readonly="readonly"/></div></td>' +
					'<td><div>Seconds:<input class="int" type="text" name="time[]" autocomplete="off">'	+
					'<input class="val" type="hidden" name="id[]" value="-1">' +
					'<input class="position" name="position[]" type="hidden" value="'+position+'"></div></td>' +
					'<td><div class="col">RGB: <input name="rgb[]" autocomplete="off" type="text" readonly="readonly"/></div></td>' +
					'<td><div class="color" style="width:65px; height=65px; background-color:white;"></div></td>' +
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
		</script>
		<div style="clear:both; float:right; padding-top:50px; padding-right:45px">
			<button class="button submit-button" type="submit" onsubmit="validate()">Save</button>
		</div>
	</form>
	<div style="float:right;  padding-top:50px;">
		<button class="button" onclick="addRow()">Add another colour!</button>
	</div>
</div>
<script>
	var submit=$('.submit-button');
	submit.on('click',function(){
		if(!validate('#haha')){
			alert('Seconds should be number!');
		}
	});

	var inputArray = new Array();
	var currentRow = null;
	$('table').on("click","tr",function(){
		inputArray = $(this).find('input');
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
		var rgb=R + ',' + G + ',' + B;
		inputArray.last().val(rgb);
		function rgbToHex(R,G,B) {return toHex(R)+toHex(G)+toHex(B)}
		function toHex(n) {
			n = parseInt(n,10);
			if (isNaN(n)) return "00";
			n = Math.max(0,Math.min(n,255));return "0123456789ABCDEF".charAt((n-n%16)/16) + "0123456789ABCDEF".charAt(n%16);
		}
		var hex = rgbToHex(R,G,B);
		inputArray.first().val('#' + hex);
		$(currentRow[0]).css("background-color" , "#" + hex);
	});
	$('table').on("hover",function(){
		$('tr .color').mousedown(function(){
			var pos = $(this).position();

			var width=$('input').outerWidth();

			$("#canvas_picker").css({
				width: width,
				position: "absolute",
				top: pos.top + 65 + "px",
				left: (pos.left)  + "px"
			}).toggle();
		});
	});
</script>
