<form id="updateForm">
	<table width="100%" cellspacing="0" style="font-size: 15px;">
		<tr>
			<td>
				<label>Tanggal Pesanan</label>
			</td>
			<td>
				<input type="text" id="tgl_pesanan" name="tgl_pesanan" value="<?= date('d-m-Y', strtotime($pelanggan->tgl_pesanan)) ?>" class="FormElement ui-widget-content ui-corner-all hasDatePicker" required autocomplete="off" maxlength="10">
			</td>
		</tr>
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
					<a href="javascript:" onclick="addRow();setNumericFormat()">
						<span class="ui-icon ui-icon-plus"></span>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</form>

<script type="text/javascript">

	$(document).ready(function() {

		setDateFormat()
		setNumericFormat()
	})

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

	function setDateFormat() {
		//date format
		$('.hasDatePicker').datepicker({
			dateFormat: 'd-m-yy',
			yearRange: '2000:2099'
		}).inputmask({
			inputFormat: "dd-mm-yyyy",
			alias: "datetime",
			minYear: '01-01-2000'
		})
		.focusout(function(e) {
			let val = $(this).val()
			if (val.match('[a-zA-Z]') == null) {
				if (val.length == 8) {
					$(this).inputmask({
						inputFormat: "dd-mm-yyyy",
					}).val([val.slice(0, 6), '20', val.slice(6)].join(''))
				}
			} else {
				$(this).focus()
			}
		})
		.focus(function() {
			let val = $(this).val()
			if (val.length == 10) {
				$(this).inputmask({
					inputFormat: 'dd-mm-yyyy',
				}).val([val.slice(0, 6), '', val.slice(8)].join(''))
			}
		})
	}

	function setNumericFormat() {
		//numeric format
		$('.im-numeric').keypress(function(e){
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			  return false;
			}
		})

		//currency format
		$('.im-currency').inputmask('integer', {
			alias: 'numeric',
			groupSeparator: '.',
			autoGroup: true,
			digitsOptional: false,
			allowMinus: false,
			placeholder: '',
		})
	}
</script>
