# AjaxCrud
An Ajax engine for inline Creating, Reading, Updating, and Deleting of records

See example code for examples of implementation

**Requirements:**
* Datatables Jquery plugin https://www.datatables.net/
* NiftyModals http://tympanus.net/Development/ModalWindowEffects/

**Requirements for code:**
* A delete modal in the format of /laravel-example/resources/views/layouts/delete_modal.blade.php

**Recommendations in code:**
- An 'App.activate' function in the style of /laravel-example/public/js/behaviour/general.js

**Hooks:**
- saveRowRequestDone
- editRowRequestDone
- addNewRowRequestDone
- saveNewRowsRequestDone
- deleteNewRowDone

**Event class documentation:**
- '.ads-edit-row' replaces the current row with an editable row
- '.ads-prep-del-row' opens the delete modal and populates it with info from the current row
- '.ads-save-row' saves the current editable row to the database and replaces it with a non-editable row
- '.ads-cancel-row' replaces the current editable row with a non-editable row without saving data
- '.ads-delete' deletes the row that is currently populated in the delete modal
- '.ads-del-new-row' deletes the current new row so that row will not be saved upon saving new rows
- '.ads-add-new-row' adds a new row in preperation for saving new rows
- '.ads-save-new-rows' saves all new rows to the database
    
**Data class documentation:**
- '.value-field' indicates that the element should have it's value harvested and added to validation
- '.focus-field' indicates that the element should be focused on when a row appears
- '.delete-header' indicates the element whose text should be pasted into the delete modal header
- '.delete-body' indicates the element whose text should be pasted into the delete modal body
- '.edit-row' indicates an editable row (used for both edit and new rows)
- '.view-row' indicates a non-editable row

**Required attributes documentation:**
- 'data-id' Must be set for editable and non-editable rows (not new rows) must be set to the database row id
    
