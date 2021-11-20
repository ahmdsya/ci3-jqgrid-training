<form id="updateForm">
	<table width="100%" cellspacing="0" style="font-size: 15px;">
		<tr>
			<td>
				<label>Nama Lengkap</label>
			</td>
			<td>
				<input type="text" id="nama" name="nama" value="<?= $pelanggan->nama ?>" required class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>NIK</label>
			</td>
			<td>
				<input type="number" id="nik" name="nik" value="<?= $pelanggan->nik ?>" required class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Handphone</label>
			</td>
			<td>
				<input type="text" id="hp" name="hp" value="<?= $pelanggan->hp ?>" required class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Email</label>
			</td>
			<td>
				<input type="email" id="email" name="email" value="<?= $pelanggan->email ?>" required
					class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Alamat</label>
			</td>
			<td>
				<textarea type="text" id="alamat" name="alamat" required
					class="FormElement ui-widget-content ui-corner-all"><?= $pelanggan->alamat ?></textarea>
			</td>
		</tr>
	</table>

	<br>

	<table width="100%" class="table ui-state-default" id="masterDetail" cellpadding="5" cellspacing="0" style="font-size: 15px;">
		<thead>
			<tr>
				<th>Nama Produk</th>
				<th>Harga</th>
				<th>Qty</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($pesanan as $pesan): ?>
			<tr>
				<td>
					<input type="text" name="nama_produk[]" value="<?= $pesan->nama_produk ?>" id="nama_produk" class="FormElement ui-widget-content ui-corner-all" required
						autocomplete="off">
				</td>
				<td>
					<input type="text" name="harga[]" value="<?= $pesan->harga ?>" id="harga" class="FormElement ui-widget-content ui-corner-all im-currency"
						required autocomplete="off">
				</td>
				<td>
					<input type="text" name="qty[]" value="<?= $pesan->qty ?>" id="qty" class="FormElement ui-widget-content ui-corner-all im-numeric"
						required autocomplete="off">
				</td>
				<td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash"
							onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr>
				<td colspan="3"></td>
				<td>
					<a href="javascript:" onclick="addRow();">
						<span class="ui-icon ui-icon-plus"></span>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</form>

<script type="text/javascript">

    function addRow() {
		$('#masterDetail tbody tr').last().before(`
			<tr>
				<td>
					<input type="text" name="nama_produk[]" id="nama_produk" class="FormElement ui-widget-content ui-corner-all" required autocomplete="off">
				</td>
				<td>
					<input type="text" name="harga[]" id="harga" class="FormElement ui-widget-content ui-corner-all im-currency" required autocomplete="off">
				</td>
				<td>
					<input type="text" name="qty[]" id="qty" class="FormElement ui-widget-content ui-corner-all im-numeric" required autocomplete="off">
				</td>
				<td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
			</tr>
		`)
	}
</script>
