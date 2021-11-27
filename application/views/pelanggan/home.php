<div class="container">
	<div class="row">
		<div class="col-lg-12 mt-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title inline-block">Data Pelanggan</h5>
					<input type="search" id="keyword" name="keyword"
						placeholder="Global Search..." 
						style="width: 780px; height: 30px; margin-bottom: 15px;"
						class="ui-search-input">
					<table id="jqGrid"></table>
					<div id="jqGridPager"></div>
					<div id="Dialog"></div>
					
					<br><br>

					<table id="jqGridDetails"></table>
					<div id="jqGridPagerDetails"></div>

					<div class="mt-2">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	
	$(document).ready(function () {

		$("#jqGrid").jqGrid({
			url: '<?= base_url() ?>home/get-data',
			editurl: 'clientArray',
			mtype: "GET",
			datatype: "json",
			colModel: [
				{
					label: 'ID',
					name: 'id',
					width: 30,
					editable: false,
					hidden: true,
					key: true,
				},
				{
					label: 'Tgl Pesanan',
					name: 'tgl_pesanan',
					width: 70,
					editable: true,
					formatter: 'date',
					formatoptions: {
						newformat:'d-m-Y'
					},
				},
				{
					label: 'Nama Lengkap',
					name: 'nama',
					width: 70,
					editable: true,
					editrules: {
						required: true,
						placeholder: "Nama Lengkap"
					},
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
				},
				{
					label: 'NIK',
					name: 'nik',
					width: 70,
					editable: true,
					edittype: 'number',
					editrules: {
						required: true,
						placeholder: "Nama Lengkap"
					},
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
				},
				{
					label: 'Handphone',
					name: 'hp',
					width: 90,
					editable: true,
					editrules: {
						pattern: "^[0-9\-\+\s\(\)]*$",
						required: true
					},
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
				},
				{
					label: 'Email',
					name: 'email',
					width: 100,
					editable: true,
					editrules: {
						email: true,
						required: true
					},
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
				},
				{
					label: 'Alamat',
					name: 'alamat',
					width: 80,
					editable: true,
					edittype: 'textarea',
					editrules: {
						required: true
					},
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
				},
			],
			loadComplete: function (data) {
				rowNum = $("#jqGrid").jqGrid('getGridParam', 'rowNum');
				ids = $("#jqGrid").jqGrid('getDataIDs');
				$('#jqGrid').jqGrid('setSelection', ids[0]);
				var index = 0
				document.addEventListener("keydown", function(event) {
					if(event.which == 38){ //tombol atas
						if(index != 0){
							$('#jqGrid').jqGrid('setSelection', ids[index = index-1]);
						}
					}else if(event.which == 40){ //tombol bawah
						if(index != rowNum){
							$('#jqGrid').jqGrid('setSelection', ids[index = index+1]);
						}
					}
				})
			},
			onSelectRow: function(rowid, selected) {
				if(rowid != null) {
					$("#jqGridDetails").jqGrid('setGridParam',{url: "<?= base_url() ?>home/get-all-pesanan?pelanggan_id="+rowid ,datatype: 'json'});
					$("#jqGridDetails").trigger("reloadGrid");
				}					
			},
			viewrecords: true, // show the current page, data rang and total records on the toolbar
			width: 780,
			height: 'auto',
			rowNum: 10,
			rowList: [10, 20, 30],
			rownumbers: true,
			loadonce: false, // this is just for the demo
			gridview: true,
			emptyrecords: 'Scroll to bottom to retrieve new page',
			pager: "#jqGridPager",
		});

		//master detail
		var pelanggan_id = $("#jqGrid").jqGrid('getGridParam', "selrow");
		$("#jqGridDetails").jqGrid({
			url: '<?= base_url() ?>home/get-all-pesanan?pelanggan_id='+pelanggan_id,
            mtype: "GET",
            datatype: "json",
            page: 1,
			colModel: [
                    { label: 'Nama Produk', name: 'nama_produk', width: 100 },
                    { label: 'Harga', name: 'harga', width: 75, formatter:'currency', formatoptions:{thousandsSeparator: ".", prefix: "Rp. "}},
                    { label: 'Kuantitas', name: 'qty', width: 50 },
                    { label: 'Total Harga', name: 'total_harga', width: 75, formatter:'currency', formatoptions:{thousandsSeparator: ".", prefix: "Rp. "}},
			],
			width: 780,
			rowNum: 10,
			loadonce: true,
			height: 'auto',
			viewrecords: true,
			footerrow: true,
			userDataOnFooter: true,
			caption: 'Data Pesanan',
			pager: "#jqGridDetailsPager"
		});

		$('#jqGrid').jqGrid('filterToolbar', {
			stringResult: true,
			searchOperators: false,
			searchOnEnter: false,
			beforeSearch: function(){
				document.getElementById('keyword').value = '';
			}
		});

		$('#keyword').on('keyup', function(){
			var value = $(this).val();
			document.getElementById('gs_nama').value   = '';
			document.getElementById('gs_nik').value    = '';
			document.getElementById('gs_hp').value     = '';
			document.getElementById('gs_email').value  = '';
			document.getElementById('gs_alamat').value = '';
			$("#jqGrid").jqGrid('setGridParam', {
				url: "<?= base_url() ?>home/get-data",
				editurl: 'clientArray',
				mtype: "GET",
				datatype: 'json',
				postData: {
					filters: '{"groupOp":"OR","rules":[{"field":"nama","op":"in","data":"'+value+'"},{"field":"nik","op":"in","data":"'+value+'"},{"field":"hp","op":"in","data":"'+value+'"},{"field":"email","op":"in","data":"'+value+'"},{"field":"alamat","op":"in","data":"'+value+'"}]}'
				},
				search: true,
			}).trigger('reloadGrid',[{page:1}]);
		});

		$('#jqGrid').navGrid('#jqGridPager',
			// the buttons to appear on the toolbar of the grid
			{
				edit: false,
				add: false,
				del: false,
				search: false,
				refresh: true,
				view: false,
				position: "left",
				cloneToTop: true
			}
		);
		
		$('#gsh_jqGrid_rn').append(
			'<div class="text-center"><button id="clearBtn" data-toggle="tooltip" title="Clear Search Filter!">X</button></div>'
		);

		$("#clearBtn").click(function () {
			document.getElementById('gs_nama').value   = '';
			document.getElementById('gs_nik').value    = '';
			document.getElementById('gs_hp').value     = '';
			document.getElementById('gs_email').value  = '';
			document.getElementById('gs_alamat').value = '';
			document.getElementById('gs_tgl_pesanan').value = '';
			document.getElementById('keyword').value   = '';
			$("#jqGrid").jqGrid('setGridParam', {
				datatype: 'json',
				postData: {
					filters: []
				},
				search: false,
			}).trigger('reloadGrid');
		});


		//btn report
		$('#jqGrid').navButtonAdd('#jqGridPager', {
			caption: "",
			title: "Report",
			buttonicon: "ui-icon-document",
			position: "first",
			onClickButton: function () {
				$('#Dialog').html(`
					<div class="ui-state-default" style="padding: 5px;">
						<h5> Tentukan Baris </h5>
						
						<label> Dari: </label>
						<input type="number" min="1" name="start" value="${$(this).getInd($(this).getGridParam('selrow'))}" class="ui-widget-content ui-corner-all autonumeric" style="padding: 5px; text-transform: uppercase;" required>

						<label> Sampai: </label>
						<input type="number" name="limit" value="${$(this).getGridParam('records')}" class="ui-widget-content ui-corner-all autonumeric" style="padding: 5px; text-transform: uppercase;" required>
					</div>
				`)
				.dialog({
					width   : 'auto',
					position: 'top',
					modal   : true,
					title   : "Report",
					buttons : [
						{
							text: "Report",
							click: function () {
								let start = $(this).find('input[name=start]').val()
								let limit = $(this).find('input[name=limit]').val()

								var sidx = $("#jqGrid").jqGrid('getGridParam','sortname');
								var sord = $("#jqGrid").jqGrid('getGridParam','sortorder');

								var getData = $('#jqGrid').jqGrid('getRowData');
								var data    = window.btoa(JSON.stringify(getData)); //base64 encode

								window.open('<?= base_url() ?>home/report?start='+start+'&limit='+limit+'&sidx='+sidx+'&sord='+sord+'&data='+data)
							}
						},
						{
							text: "Cancel",
							click: function () {
								$(this).dialog('close');
							}
						}
					]
				});
			}
		});

		//btn delete
		$('#jqGrid').navButtonAdd('#jqGridPager', {
			caption: "",
			title: "Delete",
			buttonicon: "ui-icon-trash",
			position: "first",
			onClickButton: function () {
				var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
				if (id) {
					$("#Dialog")
						.load('<?= base_url()?>home/del-dialog/' + id)
						.dialog({
							width   : 'auto',
							position: 'top',
							modal   : true,
							title   : "Delete Data",
							buttons : [{
									text: "Delete",
									click: function () {
										$.ajax({
											type: "POST",
											url: "<?= base_url() ?>home/delete",
											data: {
												id: id
											},
											dataType: "text",
											success: function (resultData) {}
										});
										$(this).dialog('close');
										$("#jqGrid").jqGrid('setGridParam', {
											datatype: 'json',
											postData: {
												filters: []
											},
											search: false,
										}).trigger('reloadGrid');
									}
								},
								{
									text: "Cancel",
									click: function () {
										$(this).dialog('close');
									}
								}
							]
						});
				} else {
					alert('No selected row.')
				}
			}
		});

		//btn update
		$('#jqGrid').navButtonAdd('#jqGridPager', {
			caption: "",
			buttonicon: "ui-icon-pencil",
			position: "first",
			title: "Update",
			onClickButton: function () {
				var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
				if (id){
					$("#Dialog")
					.load('<?= base_url()?>home/upd-dialog/'+id)
					.dialog({
						width   : 'auto',
						position: 'top',
						modal   : true,
						title   : "Update Data",
						buttons : [{
								text: "Save",
								click: function () {
									var data = $('#updateForm').serialize()
									$.ajax({
										type: "POST",
										url: "<?= base_url() ?>home/update/"+id,
										data: data,
										dataType: "text",
										success: function (result) {
											var res = JSON.parse(result)
											if(res.status == 'error'){
												$('#addForm').before(`
													<div id="errorBox" class="ui-state-error" style="font-size:12px">
														${res.msg}
													</div>
												`)
											}else{
												$("#Dialog").dialog('close');
												$("#jqGrid").jqGrid('setGridParam', {
													datatype: 'json',
													postData: {
														filters: []
													},
													search: false,
												}).trigger('reloadGrid');
											}
										}
									});
								}
							},
							{
								text: "Cancel",
								click: function () {
									$(this).dialog('close');
								}
							}
						]
					});
				}else {
					alert('No selected row.')
				}
			}
		});

		//btn add
		$('#jqGrid').navButtonAdd('#jqGridPager', {
			caption: "",
			buttonicon: "ui-icon-plus",
			position: "first",
			title: "Add",
			onClickButton: function () {
				$("#Dialog")
				.load('<?= base_url()?>home/add-dialog')
				.dialog({
					width   : 'auto',
					position: 'top',
					modal   : true,
					title   : "Add Data",
					buttons : [{
							text: "Submit",
							click: function () {
								var data = $('#addForm').serialize()
								console.log(data)
								$.ajax({
									type: "POST",
									url: "<?= base_url() ?>home/store",
									data: data,
									dataType: "text",
									success: function (result) {
										var res = JSON.parse(result)
										if(res.status == 'error'){
											$('#addForm').before(`
												<div id="errorBox" class="ui-state-error" style="font-size:12px">
													${res.msg}
												</div>
											`)
										}else{
											$("#Dialog").dialog('close');
											$("#jqGrid").jqGrid('setGridParam', {
												datatype: 'json',
												postData: {
													filters: []
												},
												search: false,
											}).trigger('reloadGrid');
										}
									}
								});
							}
						},
						{
							text: "Cancel",
							click: function () {
								$(this).dialog('close');
							}
						}
					]
				});
			}
		});
	});

</script>
