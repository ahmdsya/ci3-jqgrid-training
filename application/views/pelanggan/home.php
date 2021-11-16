<div class="container">
	<div class="row">
		<div class="col-lg-12 mt-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title inline-block">Data Pelanggan</h5>
					<input type="search" id="keyword" name="keyword"
						placeholder="Global Search..." 
						style="width: 780px;" 
						class="form-control form-control-sm mb-3 mt-2">
					<table id="jqGrid"></table>
					<div id="jqGridPager"></div>
					<div id="delDialog"></div>
					<div class="mt-2">
						<!-- <input type="button" value="Select row  with ID 1" onclick="selectRow()" /> -->
						<!-- <button id="exportExcel" class="btn btn-sm btn-success">Export To Excel</button>
						<button id="exportPDF" class="btn btn-sm btn-warning">Export To PDF</button> -->
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
					key: true,
					width: 30,
					editable: false,
					hidden:true,
					searchoptions: {
						sopt: ["in", "ni", "ge", "le", "eq"]
					}
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
						if(index >= 0){
							$('#jqGrid').jqGrid('setSelection', ids[index = index-1]);
							var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
							console.log(id)
						}
					}else if(event.which == 40){ //tombol bawah
						if(index <= rowNum){
							$('#jqGrid').jqGrid('setSelection', ids[index = index+1]);
							var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
							console.log(id)
						}
					}
				})
			},
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

		// document.addEventListener("keydown", function(event) {
		// 	if(event.which == 38){
		// 		$('#jqGrid').jqGrid('setSelection','1');
		// 		// console.log('Tombol Atas Di Klik')
		// 	}else if(event.which == 40){
		// 		console.log('Tombol Bawah Di Klik')
		// 	}
		// })

		$("#jqGrid").jqGrid('bindKeys', {
			"onEnter" : function( rowid ) { 
				// alert("You enter a row with id:"+rowid)
				console.log($("#jqGrid").jqGrid('getGridParam', "selrow"))
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
