/*
 * BSTable
 * @description  Javascript (JQuery) library to make bootstrap tables editable. Inspired by Tito Hinostroza's library Bootstable. BSTable Copyright (C) 2020 Thomas Rokicki
 *
 * @version 1.0
 * @author Thomas Rokicki (CraftingGamerTom), Tito Hinostroza (t-edson)
 */

"use strict";

/** @class BSTable class that represents an editable bootstrap table. */
class BSTable {

    /**
     * Creates an instance of BSTable.
     *
     * @constructor
     * @author: Thomas Rokicki (CraftingGamerTom)
     * @param {tableId} tableId The id of the table to make editable.
     * @param {options} options The desired options for the editable table.
     */
    constructor(tableId, options) {

        var defaults = {
            editableColumns: null, // Index to editable columns. If null all td will be editable. Ex.: "1,2,3,4,5"
            $addButton: null, // Jquery object of "Add" button
            onEdit: function() {}, // Called after editing (accept button clicked)
            onBeforeDelete: function() {}, // Called before deletion
            onDelete: function() {}, // Called after deletion
            onAdd: function() {}, // Called when added a new row
            advanced: { // Do not override advanced unless you know what youre doing
                columnLabel: 'Actions',
                buttonHTML: `<div class="btn-list">
                <button id="bEdit" type="button" class="btn btn-sm btn-primary">
                    <span class="fe fe-edit" > </span>
                </button>
                <button id="bDel" type="button" class="btn  btn-sm btn-danger">
                    <span class="fe fe-trash-2" > </span>
                </button>
                <button id="bAcep" type="button" class="btn  btn-sm btn-primary" style="display:none;">
                    <span class="fe fe-check-circle" > </span>
                </button>
                <button id="bCanc" type="button" class="btn  btn-sm btn-danger" style="display:none;">
                    <span class="fe fe-x-circle" > </span>
                </button>
            </div>`
            }
        };

        this.table = $('#' + tableId);
        this.options = $.extend(true, defaults, options);

        /** @private */
        this.actionsColumnHTML = '<td name="bstable-actions">' + this.options.advanced.buttonHTML + '</td>';

        //Process "editableColumns" parameter. Sets the columns that will be editable
        if (this.options.editableColumns != null) {
            // console.log("[DEBUG] editable columns: ", this.options.editableColumns);

            //Extract felds
            this.options.editableColumns = this.options.editableColumns.split(',');
        }
    }

    // --------------------------------------------------
    // -- Public Functions
    // --------------------------------------------------

    /**
     * Initializes the editable table. Creates the actions column.
     * @since 1.0.0
     */
    init() {
        this.table.find('thead tr').append('<th name="bstable-actions">' + this.options.advanced.columnLabel + '</th>'); // Append column to header
        this.table.find('tbody tr').append(this.actionsColumnHTML);

        this._addOnClickEventsToActions(); // Add onclick events to each action button in all rows

        // Process "addButton" parameter
        if (this.options.$addButton != null) {
            let _this = this;
            // Add a managed onclick event to the button
            this.options.$addButton.click(function() {
                _this._actionAddRow();
            });
        }
    }

    /**
     * Destroys the editable table. Removes the actions column.
     * @since 1.0.0
     */
    destroy() {
        this.table.find('th[name="bstable-actions"]').remove(); //remove header
        this.table.find('td[name="bstable-actions"]').remove(); //remove body rows
    }

    /**
     * Refreshes the editable table.
     *
     * Literally just removes and initializes the editable table again, wrapped in one function.
     * @since 1.0.0
     */
    refresh() {
        this.destroy();
        this.init();
    }

    // --------------------------------------------------
    // -- 'Static' Functions
    // --------------------------------------------------

    /**
     * Returns whether the provided row is currently being edited.
     *
     * @param {Object} row
     * @return {boolean} true if row is currently being edited.
     * @since 1.0.0
     */
    currentlyEditingRow($currentRow) {
        // Check if the row is currently being edited
        if ($currentRow.attr('data-status') == 'editing') {
            return true;
        } else {
            return false;
        }
    }

    // --------------------------------------------------
    // -- Button Mode Functions
    // --------------------------------------------------

    _actionsModeNormal(button) {
        $(button).parent().find('#bAcep').hide();
        $(button).parent().find('#bCanc').hide();
        $(button).parent().find('#bEdit').show();
        $(button).parent().find('#bDel').show();
        let $currentRow = $(button).parents('tr'); // get the row
        $currentRow.attr('data-status', ''); // remove editing status
    }
    _actionsModeEdit(button) {
        $(button).parent().find('#bAcep').show();
        $(button).parent().find('#bCanc').show();
        $(button).parent().find('#bEdit').hide();
        $(button).parent().find('#bDel').hide();
        let $currentRow = $(button).parents('tr'); // get the row
        $currentRow.attr('data-status', 'editing'); // indicate the editing status
    }

    // --------------------------------------------------
    // -- Private Event Functions
    // --------------------------------------------------
    _rowEdit(button) {
        let $currentRow = $(button).parents('tr');
        let $cols = $currentRow.find('td');
        if (this.currentlyEditingRow($currentRow)) return;

        let _this = this;
        let userId = $currentRow.data('user-id'); // Assuming you have a data attribute 'data-user-id' in your <tr> tag
        if (!userId) {
            // Handle case where user ID is not available (e.g., for new rows)
            userId = ''; // Set to empty string or any default value
        }

        this._modifyEachColumn(this.options.editableColumns, $cols, function($td, columnIndex) {
            let content = $td.html();
            let div = '<div style="display: none;">' + content + '</div>';
            let columnName = $(_this.table.find('th')[columnIndex]).text();
            let input = '<input type="hidden" name="user_id" value="' + userId + '">' + // Hidden user ID field
                        '<input class="form-control input-sm" data-original-value="' + content + '" value="' + content + '" name="' + columnName + '">';
            $td.html(div + input);
        });
        this._actionsModeEdit(button);
    }

    _rowDelete(button) {
        // Remove the row
        let $currentRow = $(button).parents('tr'); // access the row
        this.options.onBeforeDelete($currentRow);
        $currentRow.remove();
        this.options.onDelete();
    }
    _rowAccept(button) {
        let $currentRow = $(button).parents('tr');
        let $cols = $currentRow.find('td');

        if (!this.currentlyEditingRow($currentRow)) return;

        let rowData = {
            user_id: $currentRow.find('input[name="user_id"]').val()
        };

        $cols.each(function() {
            let $td = $(this);
            let fieldName = $td.attr('name');
            if (fieldName && fieldName !== 'bstable-actions' && fieldName !== 'user_id') {
                let fieldValue = $td.find('input').val();
                rowData[fieldName] = fieldValue;
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let _this = this;
        $.ajax({
            url: formUrl, // formUrl is defined in your blade file
            type: 'POST',
            data: rowData,
            success: function(response) {
                console.log('Data saved successfully:', response);
                $cols.each(function() {
                    let $td = $(this);
                    let fieldName = $td.attr('name');
                    if (fieldName && fieldName !== 'bstable-actions') {
                        let fieldValue = $td.find('input').val();
                        $td.html(fieldValue);
                    }
                });
                _this._actionsModeNormal(button);
                _this.options.onEdit($currentRow[0]);
            },
            error: function(xhr, status, error) {
                console.error('Error saving data:', error);
            }
        });
    }
    _rowCancel(button) {
        // Reject the changes
        let $currentRow = $(button).parents('tr'); // access the row
        let $cols = $currentRow.find('td'); // read fields
        if (!this.currentlyEditingRow($currentRow)) return; // not currently editing, return

        // Finish editing the row & delete changes
        this._modifyEachColumn(this.options.editableColumns, $cols, function($td) { // modify each column
            let cont = $td.find('div').html(); // read div content
            $td.html(cont); // set the content and remove the input fields
        });
        this._actionsModeNormal(button);
    }
    _actionAddRow() {
        // Add row to this table

        let $allRows = this.table.find('tbody tr');
        if ($allRows.length == 0) { // there are no rows. we must create them
            let $currentRow = this.table.find('thead tr'); // find header
            let $cols = $currentRow.find('th'); // read each header field
            // create the new row
            let newColumnHTML = '';
            $cols.each(function() {
                let column = this; // Inner function this (column object)
                if ($(column).attr('name') == 'bstable-actions') {
                    newColumnHTML = newColumnHTML + actionsColumnHTML; // add action buttons
                } else {
                    newColumnHTML = newColumnHTML + '<td></td>';
                }
            });
            this.table.find('tbody').append('<tr>' + newColumnHTML + '</tr>');
        } else { // there are rows in the table. We will clone the last row
            let $lastRow = this.table.find('tr:last');
            $lastRow.clone().appendTo($lastRow.parent());
            $lastRow = this.table.find('tr:last');
            let $cols = $lastRow.find('td'); //lee campos
            $cols.each(function() {
                let column = this; // Inner function this (column object)
                if ($(column).attr('name') == 'bstable-actions') {
                    // action buttons column. change nothing
                } else {
                    $(column).html(''); // clear the text
                }
            });
        }
        this._addOnClickEventsToActions(); // Add onclick events to each action button in all rows
        this.options.onAdd();
    }

    // --------------------------------------------------
    // -- Helper Functions
    // --------------------------------------------------

    _modifyEachColumn($editableColumns, $cols, howToModify) {
        let n = 0;
        let _this = this;
        $cols.each(function() {
            n++;
            let columnIndex = $(this).index();
            if ($(this).attr('name') == 'bstable-actions') return; // exclude the actions column
            if (!isEditableColumn(columnIndex)) return; // Check if the column is editable
            howToModify($(this), columnIndex); // If editable, call the provided function
        });

        function isEditableColumn(columnIndex) {
            // Indicates if the column is editable, based on configuration
            if ($editableColumns == null) { // option not defined
                return true; // all columns are editable
            } else { // option is defined
                for (let i = 0; i < $editableColumns.length; i++) {
                    if (columnIndex == $editableColumns[i]) return true;
                }
                return false; // column not found
            }
        }
    }


    _addOnClickEventsToActions() {
        let _this = this;
        // Add onclick events to each action button
        this.table.find('tbody tr #bEdit').each(function() {
            let button = this;
            button.onclick = function() { _this._rowEdit(button) }
        });
        this.table.find('tbody tr #bDel').each(function() {
            let button = this;
            button.onclick = function() { _this._rowDelete(button) }
        });
        this.table.find('tbody tr #bAcep').each(function() {
            let button = this;
            button.onclick = function() { _this._rowAccept(button) }
        });
        this.table.find('tbody tr #bCanc').each(function() {
            let button = this;
            button.onclick = function() { _this._rowCancel(button) }
        });
    }

    // --------------------------------------------------
    // -- Conversion Functions
    // --------------------------------------------------

    convertTableToCSV(separator) {
        // Convert table to CSV
        let _this = this;
        let $currentRowValues = '';
        let tableValues = '';

        _this.table.find('tbody tr').each(function() {
            // force edits to complete if in progress
            if (_this.currentlyEditingRow($(this))) {
                $(this).find('#bAcep').click(); // Force Accept Edit
            }
            let $cols = $(this).find('td'); // read columns
            $currentRowValues = '';
            $cols.each(function() {
                if ($(this).attr('name') == 'bstable-actions') {
                    // buttons column - do nothing
                } else {
                    $currentRowValues = $currentRowValues + $(this).html() + separator;
                }
            });
            if ($currentRowValues != '') {
                $currentRowValues = $currentRowValues.substr(0, $currentRowValues.length - separator.length);
            }
            tableValues = tableValues + $currentRowValues + '\n';
        });
        return tableValues;
    }

}
