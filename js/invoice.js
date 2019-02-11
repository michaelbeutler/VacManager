function changeStatus(status) {
    var element = $('#invoiceStatus');
    element.removeClass();
    switch(status) {
        case 0:
            // Open
            element.text('Open');
            element.addClass('label label-danger');
            break;;
        case 1:
            // Pending
            element.text('Pending');
            element.addClass('label label-warning');
            break;;
        case 2:
            // Closed
            element.text('Closed');
            element.addClass('label label-success');
            break;;
    }
}

function addPosition(item, description, quantity, unitCost) {
    var element = $('#invoicePositions > tbody');
    var total = quantity * unitCost;
    element.append('<tr><td>1</td><td>'+item+'</td><td>'+description+'</td><td>'+quantity+'</td><td>'+unitCost+'</td><td class="pos-price">'+total+'</td></tr>');
    calcTotal();
}

function calcTotal() {
    var total = 0.0;
    $('#invoicePositions > tbody').find('.pos-price').each(function(index) {
        total = total + parseFloat($(this).text());
    });
    $('#invoiceTotal').html('<b>Sub-total:</b> ' + total);
}

function changeStatusSend(status) {
    changeStatus(status);
    socket.emit('INVOICE_CHANGE_STATUS', status);
}

socket.on('INVOICE_CHANGE_STATUS', function(status){
    changeStatus(status);
});

function addPositionSend(item, description, quantity, unitCost) {
    addPosition(item, description, quantity, unitCost);
    socket.emit('INVOICE_ADD_POSITION', {
        item: item,
        description: description,
        quantity: quantity,
        unitCost: unitCost
    });
}

socket.on('INVOICE_ADD_POSITION', function(data){
    addPosition(data.item, data.description, data.quantity, data.unitCost);
});