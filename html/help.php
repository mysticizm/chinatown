<div class="tabs">
    <ul>
        <li><a href="#tabs-1">Controllers</a></li>
    </ul>
	<div id="tabs-1" class="accordion">
		<h3>Command Disable LED controller</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>00</td>
					<td>00</td>
					<td>FF</td>
				</tr>
			</table>
		</div>
		<h3>Command Enable LED controller with value</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>08</td>
					<td>Value</td>
					<td>F7</td>
				</tr>
			</table>
		</div>
		<h3>Command Go to MAX level with steps</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>01</td>
					<td>80</td>
					<td>FE</td>
				</tr>
			</table>
		</div>
		<h3>Command Go to MIN level with steps</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>02</td>
					<td>80</td>
					<td>FD</td>
				</tr>
			</table>
		</div>
		<h3>Command Step Up</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>03</td>
					<td>00</td>
					<td>FC</td>
				</tr>
			</table>
		</div>
		<h3>Command Step Down</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>04</td>
					<td>00</td>
					<td>FB</td>
				</tr>
			</table>
		</div>
		<h3>Command Recall MIN level</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>06</td>
					<td>00</td>
					<td>F9</td>
				</tr>
			</table>
		</div>
		<h3>Command Recall MAX level</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>05</td>
					<td>00</td>
					<td>FA</td>
				</tr>
			</table>
		</div>
		<h3>Command SET Level after power ON-start</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>2D</td>
					<td>Value</td>
					<td>D2</td>
				</tr>
			</table>
		</div>
		<h3>Command Set actual light  level</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>28</td>
					<td>Value</td>
					<td>D7</td>
				</tr>
			</table>
		</div>
		<h3>Command Set MAX_LEVEL (limit)</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>2A</td>
					<td>Value</td>
					<td>D5</td>
				</tr>
			</table>
		</div>
		<h3>Command Set MIN_LEVEL (limit)</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>2B</td>
					<td>Value</td>
					<td>D4</td>
				</tr>
			</table>
		</div>
		<h3>Command Set level for <span style="color: #FF0000;">RED</span></h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>21</td>
					<td>Value</td>
					<td>DE</td>
				</tr>
			</table>
		</div>
		<h3>Command Set level for <span style="color: #00FF00;">GREEN</span></h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>23</td>
					<td>Value</td>
					<td>DC</td>
				</tr>
			</table>
		</div>
		<h3>Command Set level for <span style="color: #0000FF;">BLUE</span></h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>22</td>
					<td>Value</td>
					<td>DD</td>
				</tr>
			</table>
		</div>
		<h3>Command enable / disable automatic color change</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>24</td>
					<td>04 / 00</td>
					<td>Db</td>
				</tr>
			</table>
		</div>
		<h3>Command enable / disable blending for automatic color change</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>25</td>
					<td>02 / 00</td>
					<td>DA</td>
				</tr>
			</table>
		</div>
		<h3>Command enable / disable dimming for automatic color change</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>26</td>
					<td>08 / 00</td>
					<td>D9</td>
				</tr>
			</table>
		</div>
		<h3>Command set time for automatic color change</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>29</td>
					<td>Value<br/>
					x 30 sec</td>
					<td>D6</td>
				</tr>
			</table>
		</div>
		<h3>Command go to color</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>10</td>
					<td>Value<br/>
					0 - 19</td>
					<td>EF</td>
				</tr>
			</table>
		</div>
		<h3>Command Add to group 0-15</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>6X<br> X is the number of the group (0-F)</td>
					<td>00</td>
					<td>9X`<br> X` is inverted number of the group</td>
				</tr>
			</table>
		</div>
		<h3>Command Remove from group 0-15</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>7X<br> X is the number of the group (0-F)</td>
					<td>00</td>
					<td>8X`<br> X` is inverted number of the group</td>
				</tr>
			</table>
		</div>
		<h3>Command Add to group 16-31</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>4X<br> X is the number of the group (0-F)</td>
					<td>00</td>
					<td>BX`<br> X` is inverted number of the group</td>
				</tr>
			</table>
		</div>
		<h3>Command Remove from group 16-31</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>5X<br> X is the number of the group (0-F)</td>
					<td>00</td>
					<td>AX`<br> X` is inverted number of the group</td>
				</tr>
			</table>
		</div>
		<h3>Command Assign Address</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 6; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Command</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>45</td>
					<td colspan="2" class="red">8XXX - new address</td>
				</tr>
			</table>
		</div>
		
		<h3>Query Actual level</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 11; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Request</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>A0</td>
					<td>00</td>
					<td>5F</td>
				</tr>
				<tr>
					<th>Response</th>
					<td>00</td>
					<td colspan="2" class="red">address</td>
					<td>A0</td>
					<td>Current level</td>
					<td>MAX level</td>
					<td>MIN level</td>
					<td>Start level</td>
					<td style="color: #FF0000;">RED level</td>
					<td style="color: #0000FF;">BLUE level</td>
					<td style="color: #00FF00;">GREEN level</td>
				</tr>
			</table>
		</div>
		<h3>Query System work set</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 11; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Request</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>96</td>
					<td>00</td>
					<td>69</td>
				</tr>
				<tr>
					<th>Response</th>
					<td>00</td>
					<td colspan="2" class="red">address</td>
					<td>96</td>
					<td>Current level</td>
					<td>0</td>
					<td>Options</td>
					<td>GROUPS 24-31</td>
					<td>GROUPS 16-23</td>
					<td>GROUPS 8-15</td>
					<td>GROUPS 0-7</td>
				</tr>
			</table>
		</div>
		<h3>Query Version number</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 11; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Request</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>97</td>
					<td>00</td>
					<td>68</td>
				</tr>
				<tr>
					<th>Response</th>
					<td>00</td>
					<td colspan="2" class="red">address</td>
					<td>97</td>
					<td>Current level</td>
					<td>MAC 0</td>
					<td>MAC 1</td>
					<td>Phy no</td>
					<td>Phy no</td>
					<td>Production Year</td>
					<td>Production Month</td>
				</tr>
			</table>
		</div>
		<h3>Query Work time</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 11; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Request</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>99</td>
					<td>00</td>
					<td>66</td>
				</tr>
				<tr>
					<th>Response</th>
					<td>00</td>
					<td colspan="2" class="red">address</td>
					<td>99</td>
					<td>Current level</td>
					<td colspan="2">Delta time<br/>
					Value * 0.02 [sec]</td>
					<td colspan="4">Time in flash<br/>
					Value / 8 [hr]</td>
				</tr>
			</table>
		</div>
		<h3>Query All Flash Data</h3>
		<div>
			<table class="full">
				<tr>
					<th>Byte</th>
					<?php
					for ($i = 1; $i <= 20; $i++)
					{
						echo '<th>'.$i.'</th>';
					}
					?>
				</tr>
				<tr>
					<th>Request</th>
					<td>FF</td>
					<td colspan="2" class="red">address</td>
					<td>ED</td>
					<td>00</td>
					<td>12</td>
				</tr>
				<tr>
					<th>Response</th>
					<td>00</td>
					<td colspan="2" class="red">address</td>
					<td>ED</td>
					<td>Start Level</td>
					<td>Options<br/></td>
					<td>MIN LEVEL</td>
					<td>MAX LEVEL</td>
					<td>Fade rate</td>
					<td>Fade time</td>
					<td colspan="2">Real Short address</td>
					<td>GROUP (16-23)</td>
					<td>GROUP (24-31)</td>
					<td>GROUP (0-7)</td>
					<td>GROUP (8-15)</td>
					<td colspan="4">Work_TIME</td>
				</tr>
			</table>
		</div>
	</div>
</div>