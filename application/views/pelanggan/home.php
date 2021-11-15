<div class="container">
	<div class="row">
		<div class="col-lg-12 mt-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title inline-block">Data Pelanggan</h5>
					<!-- <p id="showKey"></p> -->
					<input type="search" id="keyword" name="keyword"
						placeholder="Global Search..." 
						style="width: 780px;" 
						class="form-control form-control-sm mb-3 mt-2">
					<table id="jqGrid"></table>
					<div id="jqGridPager"></div>
					<div id="delDialog"></div>
					<!-- <div class="mt-2">
						<button id="exportExcel" class="btn btn-sm btn-success">Export To Excel</button>
						<button id="exportPDF" class="btn btn-sm btn-warning">Export To PDF</button>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('#keyword').on('keyup', function(){
		var value = $(this).val();
		document.getElementById('gs_nama').value   = '';
		document.getElementById('gs_nik').value    = '';
		document.getElementById('gs_hp').value     = '';
		document.getElementById('gs_email').value  = '';
		document.getElementById('gs_alamat').value = '';
		$.ajax({
			type: "GET",
			url: "<?= base_url() ?>home/get-data?_search=true&global_search="+value+"&rows=10&page=1&sidx=&sord=asc",
			dataType: "text",
			success: function (result) {
				
			}
		});
	});
	
	$(document).ready(function () {

		$("#jqGrid").jqGrid({
			url: '<?= base_url() ?>home/get-data',
			editurl: 'clientArray',
			mtype: "GET",
			datatype: "json",
			colModel: [{
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
			viewrecords: true, // show the current page, data rang and total records on the toolbar
			width: 780,
			height: 300,
			rowNum: 10,
			rowList: [10, 20, 30],
			rownumbers: true,
			loadonce: false, // this is just for the demo
			gridview: true,
			emptyrecords: 'Scroll to bottom to retrieve new page',
			pager: "#jqGridPager",
		});
		$('#jqGrid').jqGrid('filterToolbar', {
			stringResult: true,
			searchOperators: false,
			searchOnEnter: false,
			beforeSearch: function(){
				document.getElementById('keyword').value = '';
			}
		});

		$('#jqGrid').navGrid('#jqGridPager',
			// the buttons to appear on the toolbar of the grid
			{
				edit: true,
				add: true,
				del: false,
				search: false,
				refresh: true,
				view: false,
				position: "left",
				cloneToTop: true
			},
			// options for the Edit Dialog
			{
				editCaption: "The Edit Dialog",
				recreateForm: true,
				//checkOnUpdate : true,
				//checkOnSubmit : true,
				beforeSubmit: function (postdata, form, oper) {
					// console.log(postdata)
					$.ajax({
						type: "POST",
						url: "<?= base_url() ?>home/editurl",
						data: postdata,
						dataType: "text",
						success: function (resultData) {}
					});
					return [true, ''];
				},
				closeAfterEdit: true,
				errorTextFormat: function (data) {
					return 'Error: ' + data.responseText
				}
			},
			// options for the Add Dialog
			{
				beforeSubmit: function (postdata, form, oper) {
					// console.log(postdata)
					$.ajax({
						type: "POST",
						url: "<?= base_url() ?>home/editurl",
						data: postdata,
						dataType: "text",
						success: function (resultData) {}
					});
					return [true, ''];
				},
				recreateForm: true,
				closeAfterAdd: true,
				errorTextFormat: function (data) {
					return 'Error: ' + data.responseText
				}
			});
		
		$('#gsh_jqGrid_rn').append(
			'<div class="text-center"><button id="clearBtn" data-toggle="tooltip" title="Clear Search Filter!">X</button></div>'
		);

		$("#clearBtn").click(function () {
			document.getElementById('gs_nama').value   = '';
			document.getElementById('gs_nik').value    = '';
			document.getElementById('gs_hp').value     = '';
			document.getElementById('gs_email').value  = '';
			document.getElementById('gs_alamat').value = '';
			document.getElementById('keyword').value   = '';
			$("#jqGrid").jqGrid('setGridParam', {
				datatype: 'json',
				postData: {
					filters: []
				},
				search: false,
			}).trigger('reloadGrid');
		});

		$('#jqGrid').navButtonAdd('#jqGridPager', {
			caption: "",
			buttonicon: "ui-icon-trash",
			position: "first",
			// id: 'btnDel',
			onClickButton: function () {
				var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
				// console.log(id)
				if (id) {
					$("#delDialog")
						.load('<?= base_url()?>home/get-by-id/' + id)
						.dialog({
							width: 500,
							modal: true,
							buttons: [{
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
	});

</script>
