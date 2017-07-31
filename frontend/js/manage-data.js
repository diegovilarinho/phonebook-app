$( document ).ready(function() {
    var page = 1;
    var current_page = 1;
    var total_page = 1;
    var is_ajax_fire = 0;

    $("input[name='phone']").mask('(99) Z9999-9999', {
        translation: {
            'Z': {
                pattern: /[0-9]/, optional: true
            }
        }
    });

    manageData();

    /* manage data list */
    function manageData() {
        $.ajax({
            dataType: 'json',
            url: url + 'v1/contacts',
            data: {page:page}
        }).done(function(data){
        	total_page = Math.ceil(data.total/data.per_page);
            current_page = page;

        	$('#pagination').twbsPagination({
    	        totalPages: total_page,
    	        visiblePages: data.per_page,
                'first': 'Primeira',
                'prev': 'Anterior',
                'next': 'Próxima',
                'last': 'Última',
    	        onPageClick: function (event, pageL) {
    	        	page = pageL;
                    if(is_ajax_fire != 0){
    	        	  getPageData();
                    }
    	        }
    	    });

        	manageRow(data.data);
            is_ajax_fire = 1;

        });

    }

    /* Get Page Data*/
    function getPageData() {
    	$.ajax({
        	dataType: 'json',
        	url: url + 'v1/contacts',
        	data: {}
    	}).done(function(data){
    		manageRow(data.data);
    	});
    }


    /* Add new Item table row */
    function manageRow(data) {
    	var	rows = '';
    	$.each( data, function( key, value ) {
            rows = rows + '<tr>';
            rows = rows + '<td>' + value.name + '</td>';
            rows = rows + '<td>' + value.phone + '</td>';
            rows = rows + '<td class="email-column">' + value.email + '</td>';
    	  	rows = rows + '<td data-id="' + value.id + '" class="actions">';
            rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item" title="Editar Contato">';
            rows = rows + '<i class="fa fa-pencil" aria-hidden="true"></i>';
            rows = rows + '</button>';
            rows = rows + '<button class="btn btn-danger remove-item" title="Remover Contato">';
            rows = rows + '<i class="fa fa-trash" aria-hidden="true"></i>';
            rows = rows + '</button>';
            rows = rows + '</td>';
    	  	rows = rows + '</tr>';
    	});

    	$("tbody").html(rows);

        generateChart();
    }

    function generateChart() {
        $.ajax({
            dataType: 'json',
            url: url + 'v1/contacts',
            data: {per_page: 0}
        }).done(function(data){
            var dataWithFormatedCreatedDates = $.map(data.data, function(contact) {
                var dateString = contact.created_at,
                    dateTimeParts = dateString.split(' '),
                    dateParts = dateTimeParts[0].split('-'),
                    formatedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];

                contact.created_at = formatedDate;
                
                return contact;
            });

            var agroupedObj = {};
            var contacts = data.data;
            
            for (var i = 0; i < contacts.length; ++i) {
                var obj = contacts[i];

                if (agroupedObj[obj.created_at] === undefined)
                    agroupedObj[obj.created_at] = 0;

                agroupedObj[obj.created_at]++;
            }

            var ordered = {};
            Object.keys(agroupedObj).sort().forEach(function(key) {
              ordered[key] = agroupedObj[key];
            });
            
            var created_dates = $.map(ordered, function(el, index) {
                return index;
            });

            var created_dates_length = $.map(ordered, function(el, index) {
                return el;
            });

            var ctxCreated = document.getElementById("chartCreatedAt").getContext('2d');

            return new Chart(ctxCreated, {
                type: 'line',
                data: {
                    labels: created_dates,
                    datasets: [{
                        label: 'Contatos adicionados por data',
                        data: created_dates_length,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        });
    }

    /* Create new Item */
    $(".crud-submit").click(function(e){
        e.preventDefault();

        var name = $("#create-item").find("input[name='name']").val();
        var phone = $("#create-item").find("input[name='phone']").val();
        var email = $("#create-item").find("input[name='email']").val();

        if(name != '' && phone != '' && email != ''){
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: url + 'v1/contacts',
                data:{
                    name:name, 
                    phone:phone, 
                    email:email
                }
            })
            .done(function(data) {
                $("#create-item").find("input[name='name']").val('');
                $("#create-item").find("input[name='phone']").val('');
                $("#create-item").find("input[name='email']").val('');
                
                if(data.status == 'success') {
                    getPageData();

                    $(".modal").modal('hide');

                    toastr.success(data.message, 'Feito! ;)', {timeOut: 5000});
                } else {
                    toastr.error(data.message, 'Opss!', {timeOut: 5000});
                }
            });
        } else {
            alert('Verifique se você preencheu todos os campos obrigatórios.')
        }


    });

    /* Remove Item */
    $("body").on("click",".remove-item",function(){
        var id = $(this).parent('td').data('id');

        var c_obj = $(this).parents("tr");

        $.ajax({
            dataType: 'json',
            type:'DELETE',
            url: url + 'v1/contacts/' + id,
            data:{id:id}
        })
        .done(function(data){
            if(data.status == 'success') {
                c_obj.remove();
                toastr.success(data.message, 'Feito! ;)', {timeOut: 5000});
            } else {
                toastr.error(data.message, 'Opss!', {timeOut: 5000});
            }

            getPageData();
        });

    });


    /* Edit Item */
    $("body").on("click", ".edit-item", function() {
        var id = $(this).parent("td").data('id');
        var name = $(this).parent("td").prev("td").prev("td").prev("td").text();
        var phone = $(this).parent("td").prev("td").prev("td").text();
        var email = $(this).parent("td").prev("td").text();

        $("#edit-item").find("input[name='name']").val(name);
        $("#edit-item").find("input[name='phone']").val(phone);
        $("#edit-item").find("input[name='email']").val(email);
        $("#edit-item").find(".edit-id").val(id);
    });


    /* Updated new Item */
    $(".crud-submit-edit").click(function(e){
        e.preventDefault();

        var name = $("#edit-item").find("input[name='name']").val();
        var phone = $("#edit-item").find("input[name='phone']").val();
        var email = $("#edit-item").find("input[name='email']").val();
        var id = $("#edit-item").find(".edit-id").val();

        if(name != '' && phone != '' && email != '') {
            $.ajax({
                dataType: 'json',
                type:'PUT',
                url: url + 'v1/contacts/' + id,
                data:{
                    name: name, 
                    phone: phone,
                    email: email
                }
            })
            .done(function(data){
                if(data.status == 'success') {
                    getPageData();

                    $(".modal").modal('hide');

                    toastr.success(data.message, 'Feito! ;)', {timeOut: 5000});
                } else {
                    toastr.error(data.message, 'Opss!', {timeOut: 5000});
                }
            });
        }else{
            alert('Confira se você preencheu todos os dados corretamente.')
        }

    });
});