function setPointer(theRow, thePointerColor) {
    if (thePointerColor == '' || typeof(theRow.style) == 'undefined') return false;
    var theCells = '';
    if (typeof(document.getElementsByTagName) != 'undefined') theCells = theRow.getElementsByTagName('td');
    else if (typeof(theRow.cells) != 'undefined') theCells = theRow.cells;
    else return false;
    var rowCellsCnt = theCells.length;
    for (var c = 0; c < rowCellsCnt; c++) {
        theCells[c].style.backgroundColor = thePointerColor;
    }
    return true;
}

// open a record in the list for normal edit function
function EditRecordInList(id) {
    document.cmsform.id.value = id;
    document.cmsform.submit();
}

// open a record in the list for normal edit function as component / popup mode
function EditRecordAsComponent(id) {
    document.getElementById('cmsform').target = '_self';
    document.cmsform.id.value = id;
    document.cmsform.pagedef.value = 'tableeditorPopup';
    document.cmsform.submit();
}

// open the template engine for page "id"
function OpenTemplateEngine(id) {
    document.cmsform.id.value = id;
    document.cmsform.pagedef.value = 'templateengine';
    document.cmsform.submit();
}

function selectExtendedLookupRecord(id) {
    document.cmsformAjaxCall.id.value = id;
    var hiddenField = parent.document.getElementById(document.cmsformAjaxCall.fieldName.value);
    hiddenField.value = id;
    document.cmsformAjaxCall._fnc.value = 'GetDisplayValue';
    PostAjaxForm('cmsformAjaxCall', selectExtendedLookupRecordClose);
}

function selectExtendedLookupMultiTableRecord(id) {
    document.cmsformAjaxCall.id.value = id;
    var hiddenField = parent.document.getElementById(document.cmsformAjaxCall.fieldName.value);
    hiddenField.value = id;
    var hiddenFieldTableName = parent.document.getElementById(document.cmsformAjaxCall.fieldName.value + '_table_name');
    hiddenFieldTableName.value = document.cmsformAjaxCall.tableid.value;
    document.cmsformAjaxCall._fnc.value = 'GetDisplayValue';
    PostAjaxForm('cmsformAjaxCall', selectExtendedLookupMultiTableRecordClose);
}

function selectExtendedLookupRecordClose(data, statusText) {
    var fieldDisplayValue = parent.document.getElementById(document.cmsformAjaxCall.fieldName.value + 'CurrentSelection');
    fieldDisplayValue.innerHTML = data;
    parent.CloseModalIFrameDialog();
}

function selectExtendedLookupMultiTableRecordClose(data, statusText) {
    var fieldDisplayValue = parent.document.getElementById(document.cmsformAjaxCall.fieldName.value + 'CurrentSelection');
    var hiddenFieldTableName = parent.document.getElementById('aTableNames[' + document.cmsformAjaxCall.tableid.value + ']');
    fieldDisplayValue.innerHTML = hiddenFieldTableName.value + ' - ' + data;
    parent.CloseModalIFrameDialog();
}

function DeleteRecord(id) {
    var message = CHAMELEON.CORE.i18n.Translate('chameleon_system_core.js.confirm_delete');

    if (confirm(message)) {
        CHAMELEON.CORE.showProcessingModal();
        document.cmsformdel.elements['module_fnc[contentmodule]'].value = 'Delete';
        document.cmsformdel.id.value = id;
        document.cmsformdel.submit();
    }
}


function DeleteSelectedRecords(formName) {
    var IDs = '';
    $("input[name='aInputIdList[]']").each(function () {
            if (this.checked) {
                if (IDs != '') IDs = IDs + ',';
                IDs = IDs + this.value;
            }
        }
    );

    if (IDs != '') {
        var message = CHAMELEON.CORE.i18n.Translate("chameleon_system_core.js.confirm_multi_delete");
        if (confirm(message)) {
            document.cmsformworkonlist.elements['module_fnc[contentmodule]'].value = 'DeleteSelected';
            document.cmsformworkonlist.items.value = IDs;
            document.cmsformworkonlist.submit();
        }
    } else {
        toasterMessage(CHAMELEON.CORE.i18n.Translate('chameleon_system_core.js.error_no_selection'), 'WARNING');
    }
}


function ChangeListMarking(fieldValue, formName) {
    var searchId = "#" + formName;

    $("input[name='aInputIdList[]']").each(function () {
            this.checked = fieldValue;
        }
    );
}

/**
 * force links inside table cells to fixed height based on TR height
 */
$(document).ready(function () {
    CHAMELEON.CORE.handleFormAndLinkTargetsInModals();

    $('.TCMSListManagerFullGroupTable tr.TGroupTableItemRow').each(function () {
        var TRHeight = $(this).innerHeight() - 13; // - TD padding and borders
        $(this).find('a.TGroupTableLink').css('height', TRHeight);
    });

    $('[data-select2-option]').each(function () {
        $(this).select2($(this).data('select2-option'));
    });

    $('.submitOnSelect').on('select2:select', function (e) {
        $(this).closest('form').submit();
    });

    var searchLookup = $('select#searchLookup');

    searchLookup.select2({
        placeholder: searchLookup.data('select2-placeholder'),
        allowClear: true,
        ajax: {
            url: searchLookup.data('select2-ajax'),
            dataType: 'json',
            delay: 150,
            processResults: function (data) {
                return {
                    results: JSON.parse(data)
                };
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.html;
        },
        tags: true,
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            }
        }
    }).on('select2:select', function (e) {
        if (e.params.data.newOption) {
            var listname = searchLookup.data('listname');
            document[listname]._startRecord.value = 0;
            document[listname].submit();
        } else {
            var id = e.params.data.id.trim();

            if ('' === id) {
                return;
            }

            switchRecord(id);
        }
    }).on('select2:unselect', function (e) {
        // :unselect and submit() doesn't transmit the empty searchlookup-Field. Therefore, the value of
        // _search_word is not overwritten (not reset) in the session. With an additional hidden field it works.
        var input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', '_search_word');

        var listname = searchLookup.data('listname');
        document[listname].appendChild(input);
        document[listname]._startRecord.value = 0;
        document[listname].submit();
    });

    function switchRecord(id) {
        if ('' !== id) {
            var url = searchLookup.data('record-url') + '&id=' + id;
            top.document.location.href = url;
        }
    }
});