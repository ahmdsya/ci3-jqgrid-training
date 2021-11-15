<form name="FormPost" id="FrmGrid_jqGrid" class="FormGrid" onsubmit="return false;" style="width:auto;height:auto;">
	<div class="FormError ui-state-error" style="display:none;"></div>
	<div class="tinfo topinfo"></div>
	<table id="TblGrid_jqGrid" class="EditTable ui-common-table">
		<tbody>
			<tr rowpos="1" class="FormData" id="tr_nama">
				<td class="CaptionTD"><label for="nama">Nama Lengkap</label></td>
				<td class="DataTD"><input type="text" role="textbox" value="<?= $pelanggan->nama ?>" readonly id="nama" name="nama" rowid="_empty" module="form"
						checkupdate="false" size="20" class="FormElement ui-widget-content ui-corner-all"></td>
			</tr>
			<tr rowpos="2" class="FormData" id="tr_nik">
				<td class="CaptionTD"><label for="nik">NIK</label></td>
				<td class="DataTD"><input type="number" role="textbox" value="<?= $pelanggan->nik ?>" readonly id="nik" name="nik" rowid="_empty" module="form"
						checkupdate="false" size="20" class="FormElement ui-widget-content ui-corner-all"></td>
			</tr>
			<tr rowpos="3" class="FormData" id="tr_hp">
				<td class="CaptionTD"><label for="hp">Handphone</label></td>
				<td class="DataTD"><input type="text" role="textbox" value="<?= $pelanggan->hp ?>" id="hp" name="hp" rowid="_empty" module="form"
						checkupdate="false" size="20" class="FormElement ui-widget-content ui-corner-all"></td>
			</tr>
			<tr rowpos="4" class="FormData" id="tr_email">
				<td class="CaptionTD"><label for="email">Email</label></td>
				<td class="DataTD"><input type="text" role="textbox" value="<?= $pelanggan->email ?>" readonly id="email" name="email" rowid="_empty"
						module="form" checkupdate="false" size="20" class="FormElement ui-widget-content ui-corner-all">
				</td>
			</tr>
			<tr rowpos="5" class="FormData" id="tr_alamat">
				<td class="CaptionTD"><label for="alamat">Alamat</label></td>
				<td class="DataTD"><textarea role="textbox" multiline="true" id="alamat" name="alamat" rowid="_empty"
						module="form" checkupdate="false" cols="20" rows="2"
						class="FormElement ui-widget-content ui-corner-all" readonly><?= $pelanggan->alamat ?></textarea></td>
			</tr>
			<tr class="FormData" style="display:none">
				<td class="CaptionTD"></td>
				<td colspan="1" class="DataTD"><input class="FormElement" id="id_g" type="text" name="jqGrid_id"
						value="_empty"></td>
			</tr>
		</tbody>
	</table>
	<div class="binfo topinfo bottominfo"></div>
</form>
