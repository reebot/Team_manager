<table width="100%" class="NeatTable">

	<thead>
		<tr>
			<th>E-Mail</th>
			<th>Joined</th>
		</tr>
	</thead>

	<tbody>

		<?php foreach( $this->Invites as $Invite ): ?>

			<tr>
				<td><?php echo $Invite->email ?></td>
				<td><?php echo ( $Invite->joined ) ? "Yes (".date("jS \of F o H:i", $Invite->joined_on).")" : "Not Yet" ?></td>
			</tr>

		<?php endforeach ?>

	</tbody>

</table>