<form>
	<table width="100%" cellspacing="0" id="customerData" style="font-size: 15px;">
		<tr>
			<td>
				<label>Nama Lengkap</label>
			</td>
			<td>
				<input type="text" id="nama" name="nama" value="<?= $pelanggan->nama ?>" readonly
					class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>NIK</label>
			</td>
			<td>
				<input type="text" id="nik" name="nik" value="<?= $pelanggan->nik ?>" readonly
					class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Handphone</label>
			</td>
			<td>
				<input type="text" id="hp" name="hp" value="<?= $pelanggan->hp ?>" readonly
					class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Email</label>
			</td>
			<td>
				<input type="text" id="email" name="email" value="<?= $pelanggan->email ?>" readonly
					class="FormElement ui-widget-content ui-corner-all">
			</td>
		</tr>
		<tr>
			<td>
				<label>Alamat</label>
			</td>
			<td>
				<textarea type="text" id="alamat" name="alamat" readonly
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
					<input type="text" name="nama_produk[]" value="<?= $pesan->nama_produk ?>" readonly id="nama_produk" class="FormElement ui-widget-content ui-corner-all" required
						autocomplete="off">
				</td>
				<td>
					<input type="text" name="harga[]" value="<?= $pesan->harga ?>" id="harga" readonly class="FormElement ui-widget-content ui-corner-all im-currency"
						required autocomplete="off">
				</td>
				<td>
					<input type="text" name="qty[]" value="<?= $pesan->qty ?>" id="qty" readonly class="FormElement ui-widget-content ui-corner-all im-numeric"
						required autocomplete="off">
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</form>
