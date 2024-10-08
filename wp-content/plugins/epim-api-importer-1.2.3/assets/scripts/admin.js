function localAsUtc(date) {
    if (isNotValidDate(date)) {
        return null;
    }

    return new Date(Date.UTC(
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
        date.getHours(),
        date.getMinutes(),
        date.getSeconds(),
        date.getMilliseconds()
    ));
}


function isNotValidDate(date) {
    return date == null || isNaN(date.getTime());
}

adminJQ = jQuery.noConflict();

adminJQ(function ($) {

    let debug = false;
    let cMax = 5;

    function _o(text) {
        $('#ePimResult').prepend(text + '<br>');
    }

    let oneProductLinkImages = new ts_execute_queue('#ePimResult',function() {
        _o('Finished');
    },function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='product_ID_code') {
            this.queue(ajaxurl,{action: 'product_group_image_link',productID:data});
        }
    });

    let oneProductQueue = new ts_execute_queue('#ePimResult', function () {
        $('.modal.UpdateCode').removeClass('active');
        _o('Finished');

    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if (action === 'product_ID_code') {
            this.queue(ajaxurl, {action: 'get_product', ID: data});
        }
        if (action === 'get_product') {
            let product = JSON.parse(data);
            let obj = this;
            $(product.VariationIds).each(function (index, variationID) {
                obj.queue(ajaxurl, {
                    action: 'create_product',
                    productID: product.Id,
                    variationID: variationID,
                    bulletText: product.BulletText,
                    productName: product.Name,
                    categoryIDs: product.CategoryIds,
                    pictureIDs: product.PictureIds
                });
            });
        }
    });

    let updateAllProducts = new ts_execute_queue('#ePimResult', function () {
        _o('<strong>All Finished</strong>');
        $('.modal.CreateAll').removeClass('active');
        $('.modal.CreateAllProducts').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='sort_categories') {
            //updateAllProducts.queue(ajaxurl,{action: 'cat_image_link'});
            _o('Getting Product Data from ePim...');
            updateAllProducts.queue(ajaxurl,{action: 'get_all_products'});
        }
        if(action==='cat_image_link') {
            //updateAllProducts.queue(ajaxurl,{action: 'get_all_products'});
        }
        if(action==='get_all_products') {
            if ($.trim(data)) {
                let products = JSON.parse(data);
                let c = 0;
                $(products).each(function (index, product) {
                    $(product.VariationIds).each(function (index, variationID) {
                        updateAllProducts.queue(ajaxurl,{
                            action: 'create_product',
                            productID: product.Id,
                            variationID: variationID,
                            bulletText: product.BulletText,
                            productName: product.Name,
                            categoryIDs: product.CategoryIds,
                            pictureIDs: product.PictureIds
                        });
                    });
                    if (debug) {
                        c++;
                        if (c >= cMax) {
                            return false;
                        }
                    }
                });
            }
        }
    });

    let updateSinceProducts = new ts_execute_queue('#ePimResult', function () {
        _o('<strong>All Finished</strong>');
        $('.modal.UpdateSince').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        /*if(action==='sort_categories') {
            updateSinceProducts.queue(ajaxurl,{action: 'cat_image_link'});
        }
        if(action==='cat_image_link') {
            let dpDate = $('.custom_date').datepicker('getDate');
            let dateUtc = localAsUtc(dpDate);
            let iso = dateUtc.toISOString();
            updateSinceProducts.queue(ajaxurl,{action: 'get_all_changed_products_since', timeCode: iso});
        }*/
        if(action==='get_all_changed_products_since_starting'){
            if( data!='[]' ) {
                //window.console.log(data);
                if ($.trim(data)) {
                    let products = JSON.parse(data);
                    let c = 0;
                    let results = products['Results'];
                    if (results) {
                        $(results).each(function (index, product) {
                            $(product.VariationIds).each(function (index, variationID) {
                                updateSinceProducts.queue(ajaxurl, {
                                    action: 'create_product',
                                    productID: product.Id,
                                    variationID: variationID,
                                    bulletText: product.BulletText,
                                    productName: product.Name,
                                    categoryIDs: product.CategoryIds,
                                    pictureIDs: product.PictureIds
                                });
                            });
                            if (debug) {
                                c++;
                                if (c >= cMax) {
                                    return false;
                                }
                            }
                        });
                    }
                }
            }
        }
        if(action==='get_all_changed_products_since') {
            if( data!='[]' ) {
                //window.console.log(data);
                if ($.trim(data)) {
                    let products = JSON.parse(data);
                    let c = 0;
                    let results = products['Results'];
                    if(results){
                        let totalResults = products.TotalResults;
                        let limit = products.Limit;
                        if(limit==0) limit = 1;
                        let pages = Math.ceil(totalResults/limit);
                        let timeCodeStart = request.indexOf('timeCode=');
                        let timeCode = '';
                        if (timeCodeStart>-1){
                            timeCodeStart += 9;
                            timeCode = request.substr(timeCodeStart,24);
                            //window.console.log(timeCode);
                        }
                        if (timeCode != '') {
                            for (let i = 1; i<= pages; i++){
                                updateSinceProducts.queue(ajaxurl, {
                                    action: 'get_all_changed_products_since_starting',
                                    start: i*limit,
                                    timeCode: timeCode,
                                })
                            }
                        }

                        $(results).each(function (index, product) {
                            $(product.VariationIds).each(function (index, variationID) {
                                updateSinceProducts.queue(ajaxurl, {
                                    action: 'create_product',
                                    productID: product.Id,
                                    variationID: variationID,
                                    bulletText: product.BulletText,
                                    productName: product.Name,
                                    categoryIDs: product.CategoryIds,
                                    pictureIDs: product.PictureIds
                                });
                            });
                            if (debug) {
                                c++;
                                if (c >= cMax) {
                                    return false;
                                }
                            }
                        });
                    }

                } else {
                    _o('<strong>No Products Found to Update or Create');
                    updateSinceProducts.processFinished = false;
                }
            } else {
                _o('<strong>No Products Found to Update or Create');
                updateSinceProducts.processFinished = false;
            }
            //updateAllProducts.queue(ajaxurl,{action: 'get_all_products'});
        }
    });

    /*let updateSinceQueue = new ts_execute_queue('#ePimResult', function () {
        _o('Category Data Imported');
        updateSinceProducts.reset();
        updateSinceProducts.queue(ajaxurl,{action: 'sort_categories'});
        updateSinceProducts.process();
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='get_all_categories') {
            let categories = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(categories).each(function (index, record) {
                obj.queue(ajaxurl,{action: 'create_category', ID: record.Id, name: record.Name, ParentID: record.ParentId, picture_ids: record.PictureIds});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }

        if (action === 'create_category') {
            let r = decodeURIComponent(request);
            let ro = QueryStringToJSON('?' + r);
            let id = ro.ID;
            this.queue(ajaxurl,{action: 'get_category_images', ID: id});
        }
        if (action === 'get_category_images') {
            if ($.trim(data)) {
                let pictures = JSON.parse(data);
                let obj = this;
                $(pictures).each(function (index, picture) {
                    obj.queue(ajaxurl,{action: 'get_picture_web_link', ID: picture});
                })
            }
        }
        if (action === 'get_picture_web_link') {
            if ($.trim(data)) {
                let pictures = JSON.parse(data);
                let obj = this;
                $(pictures).each(function (index, picture) {
                    obj.queue(ajaxurl,{action: 'import_picture', ID: picture.Id, weblink: picture.Path});
                })

            }
        }
    });*/

    let updateAllQueue = new ts_execute_queue('#ePimResult', function () {
        _o('Category Data Imported');
        _o('Sorting Categories...')
        updateAllProducts.reset();
        updateAllProducts.queue(ajaxurl,{action: 'sort_categories'});
        updateAllProducts.process();
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='get_all_categories') {
            let categories = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(categories).each(function (index, record) {
                obj.queue(ajaxurl,{action: 'create_category', ID: record.Id, name: record.Name, ParentID: record.ParentId, picture_ids: record.PictureIds, alias: record.Alias});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
    });

    let backgroundUpdateQueue = new ts_execute_queue('#ePimResult', function () {
        /*_o('Current Status Retrieved');*/
        $('.modal.GetCurrentUpdateData').removeClass('active');
        $('.modal.BackgroundUpdateAll').removeClass('active');
        $('.modal.StopCurrentUpdate').removeClass('active');
        $('.modal.BackgroundUpdateSince').removeClass('active');
        $('.modal.BackgroundUnfreezeQueue').removeClass('active');
        $('.modal.BackgroundImportByID').removeClass('active');
        $('.modal.BackgroundUpdateAttributes').removeClass('active');
    }, function (action, request, data) {
        _o(data);
    });

    let updateCatDetailsQueue = new ts_execute_queue('#ePimResult', function () {
        _o('Category Data Imported - Finished.');
        $('.modal.CreateCategories').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
    });

    let updateCategoriesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('Sorting Categories...');
        updateCatDetailsQueue.reset();
        updateCatDetailsQueue.queue(ajaxurl,{action: 'sort_categories'});
        updateCatDetailsQueue.process();
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='get_all_categories') {
            let categories = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(categories).each(function (index, record) {
                obj.queue(ajaxurl,{action: 'create_category', ID: record.Id, name: record.Name, ParentID: record.ParentId, picture_ids: record.PictureIds, alias: record.Alias});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
        if (action === 'create_category') {
            let r = decodeURIComponent(request);
            let ro = QueryStringToJSON('?' + r);
            let id = ro.ID;
            this.queue(ajaxurl,{action: 'get_category_images', ID: id});
        }
        if (action === 'get_category_images') {
            if ($.trim(data)) {
                let pictures = JSON.parse(data);
                let obj = this;
                $(pictures).each(function (index, picture) {
                    obj.queue(ajaxurl,{action: 'get_picture_web_link', ID: picture});
                })
            }
        }
        if (action === 'get_picture_web_link') {
            if ($.trim(data)) {
                let pictures = JSON.parse(data);
                let obj = this;
                $(pictures).each(function (index, picture) {

                    obj.queue(ajaxurl,{action: 'import_picture', ID: picture.Id, weblink: picture.WebPath});
                })

            }
        }
    });

    let customQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.deleteAttributes').removeClass('active');
        $('.modal.deleteCategories').removeClass('active');
        $('.modal.deleteImages').removeClass('active');
        $('.modal.deleteProducts').removeClass('active');
        $('.modal.ClearProducts').removeClass('active');
        $('.modal.deleteOrphanedImages').removeClass('active');
        $('#ClearProducts').prop('disabled',false);
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
    });

    let attributesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.deleteAttributes').removeClass('active');
        $('.modal.deleteCategories').removeClass('active');
        $('.modal.deleteImages').removeClass('active');
        $('.modal.deleteProducts').removeClass('active');
        $('.modal.ClearProducts').removeClass('active');
        $('.modal.deleteOrphanedImages').removeClass('active');
        $('#ClearProducts').prop('disabled',false);
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        //_o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(data=='Timed Out') {
            _o('<br>Still working...Please wait');
            let obj = this;
            obj.queue(ajaxurl,{action: 'delete_attributes'});
        }
    });

    let branchesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.CreateBranches').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);

        if(action==='get_all_branches') {
            let branches = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(branches).each(function (index, record) {
                let Address1 = record.Address1;
                let Address2 = record.Address2;
                let City = record.City;
                let County = record.County;
                let Postcode = record.Postcode;

                let address = '';
                if(Address1 != '') address += Address1 + '<br>';
                if(Address2 != '') address += Address2 + '<br>';
                if(City != '') address += City + '<br>';
                if(County != '') address += County + '<br>';
                if(Postcode != '') address += Postcode;

                obj.queue(ajaxurl,{action: 'create_branch', ID: record.Id, name: record.Name, Telephone: record.Telephone, Email: record.Email, Address: address});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
    });

    let updateBranchesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.UpdateBranchStock').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        window.console.log(action);
        if(action==='get_all_branches') {
            let branches = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(branches).each(function (index, record) {

                obj.queue(ajaxurl,{action: 'get_branch_stock', ID: record.Id});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }

        if(action==='get_branch_stock') {
            let stock = JSON.parse(data);
            let obj = this;
            let c = 0;
            let r = decodeURIComponent(request);
            let ro = QueryStringToJSON('?' + r);
            let branch_id = ro.ID;
            $(stock).each(function (index, record) {

                obj.queue(ajaxurl,{action: 'update_branch_stock', ID: branch_id, VariationId: record.VariationId, Stock: record.Stock});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
    });

    let deletedEntitiesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.DeletedStock').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        if(action==='get_deleted_entities_count') {
            let obj = this;
            obj.queue(ajaxurl,{action: 'get_deleted_entities_variations', TotalResults: data});
        }
        if(action==='get_deleted_entities_variations') {
            let jdata= data;
            if(jdata.slice(-1)!=']') {
                jdata = jdata.slice(0,-1);
            }
            if(jdata.slice(-1)!=']') {
                jdata = jdata.slice(0,-1);
            }
            if(jdata.slice(-1)!=']') {
                jdata = jdata.slice(0,-1);
            }
            let variations = JSON.parse(jdata);
            let obj = this;
            let c = 0;
            $(variations).each(function (index, record) {

                obj.queue(ajaxurl,{action: 'delete_variation', variationID: record.variationID});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
    });


    $('#DeletedStock').on('click',function () {
        _o('Getting Deleted Stock Data from ePim...');
        $('.modal.DeletedStock').addClass('active');
        deletedEntitiesQueue.reset();
        deletedEntitiesQueue.queue(ajaxurl,{action: 'get_deleted_entities_count'});
        deletedEntitiesQueue.process();
    });

    $('#UpdateBranchStock').on('click',function () {
        _o('Getting Branch Data from ePim...');
        $('.modal.UpdateBranchStock').addClass('active');
        updateBranchesQueue.reset();
        updateBranchesQueue.queue(ajaxurl,{action: 'get_all_branches'});
        updateBranchesQueue.process();
    });

    $('#CreateBranches').on('click',function () {
        _o('Getting Branch Data from ePim...');
        $('.modal.CreateBranches').addClass('active');
        branchesQueue.reset();
        branchesQueue.queue(ajaxurl,{action: 'get_all_branches'});
        branchesQueue.process();
    });

    $('#CreateAll').on('click',function () {
        _o('Getting Category Data from ePim...');
        $('.modal.CreateAll').addClass('active');
        updateAllQueue.reset();
        updateAllQueue.queue(ajaxurl,{action: 'get_all_categories'});
        updateAllQueue.process();
    });

    $('#GetCurrentUpdateData').on('click', function (){
        $('#ePimResult').html('');
        $('.modal.GetCurrentUpdateData').addClass('active');
        backgroundUpdateQueue.reset();
        backgroundUpdateQueue.queue(ajaxurl,{action: 'fast_create'});
        backgroundUpdateQueue.process();
    });

    $('#StopCurrentUpdate').on('click', function (){
        $('#ePimResult').html('');
        $('.modal.StopCurrentUpdate').addClass('active');
        backgroundUpdateQueue.reset();
        backgroundUpdateQueue.queue(ajaxurl,{action: 'stop_background_update'});
        backgroundUpdateQueue.process();
    });

    $('#BackgroundUpdateAll').on('click', function (){
        $('#ePimResult').html('');
        _o('Starting Background Update....');
        $('.modal.BackgroundUpdateAll').addClass('active');
        backgroundUpdateQueue.reset();
        $('#ePimTail').html('');
        backgroundUpdateQueue.queue(ajaxurl,{action: 'force_background_update'});
        backgroundUpdateQueue.process();
    });

    $('#BackgroundUpdateAttributes').on('click', function (){
        $('#ePimResult').html('');
        _o('Starting Attribute Update....');
        $('.modal.BackgroundUpdateAttributes').addClass('active');
        backgroundUpdateQueue.reset();
        $('#ePimTail').html('');
        backgroundUpdateQueue.queue(ajaxurl,{action: 'attribute_update'});
        backgroundUpdateQueue.process();
    });

    $('#BackgroundUnfreezeQueue').on('click', function (){
        $('#ePimResult').html('');
        _o('Starting Background Update....');
        $('.modal.BackgroundUnfreezeQueue').addClass('active');
        backgroundUpdateQueue.reset();
        $('#ePimTail').html('');
        backgroundUpdateQueue.queue(ajaxurl,{action: 'unfreeze_queue'});
        backgroundUpdateQueue.process();
    });

    $('#CreateAllProducts').on('click',function () {
        _o('Getting all products from ePim. Please wait...');
        $('.modal.CreateAllProducts').addClass('active');
        updateAllProducts.reset();
        updateAllProducts.queue(ajaxurl,{action: 'get_all_products'});
        updateAllProducts.process();
    });

    $('#CreateCategories').on('click',function () {
        _o('Getting Category Data from ePim...');
        $('.modal.CreateCategories').addClass('active');
        updateCategoriesQueue.reset();
        updateCategoriesQueue.queue(ajaxurl,{action: 'get_all_categories'});
        updateCategoriesQueue.process();
    });

    $('#UpdateCode').on('click',function () {
        _o('Getting Product Data from ePim...');
        $('.modal.UpdateCode').addClass('active');
        oneProductQueue.reset();
        oneProductQueue.queue(ajaxurl, {action: 'product_ID_code', CODE: $('#pCode').val()});
        oneProductQueue.process();
    });

    $('#UpdateSince').on('click',function () {
        updateSinceProducts.reset();
        let dpDate = $('.custom_date').datepicker('getDate');
        let dateUtc = localAsUtc(dpDate);
        let iso = dateUtc.toISOString();
        $('.modal.UpdateSince').addClass('active');
        updateSinceProducts.queue(ajaxurl,{action: 'get_all_changed_products_since', timeCode: iso});
        updateSinceProducts.process();

    });

    $('#BackgroundUpdateSince').on('click',function () {
        backgroundUpdateQueue.reset();
        let dpDate = $('.custom_date').datepicker('getDate');
        let dateUtc = localAsUtc(dpDate);
        let iso = dateUtc.toISOString();
        $('.modal.BackgroundUpdateSince').addClass('active');
        backgroundUpdateQueue.queue(ajaxurl,{action: 'get_background_changed_products_since', timeCode: iso});
        backgroundUpdateQueue.process();

    });

    $('#BackgroundImportByID').on('click',function () {
        backgroundUpdateQueue.reset();
        $('.modal.BackgroundImportByID').addClass('active');
        backgroundUpdateQueue.queue(ajaxurl,{action: 'import_by_variation_id', variation_id: $('#variation_id').val()});
        backgroundUpdateQueue.process();

    });

    $('#deleteAttributes').on('click',function(){
        _o('Deleting Attributes..');
        $('.modal.deleteAttributes').addClass('active');
        attributesQueue.reset();
        attributesQueue.queue(ajaxurl, {action: 'delete_attributes'});
        attributesQueue.process();
    });

    $('#deleteCategories').on('click',function(){
        _o('Deleting Categories..');
        $('.modal.deleteCategories').addClass('active');
        customQueue.reset();
        customQueue.queue(ajaxurl, {action: 'delete_categories'});
        customQueue.process();
    });

    $('#deleteImages').on('click',function(){
        _o('Deleting Images..');
        $('.modal.deleteImages').addClass('active');
        customQueue.reset();
        customQueue.queue(ajaxurl, {action: 'delete_epim_images'});
        customQueue.process();
    });

    $('#deleteOrphanedImages').on('click',function(){
        _o('Deleting Orphaned Images..');
        $('.modal.deleteOrphanedImages').addClass('active');
        customQueue.reset();
        customQueue.queue(ajaxurl, {action: 'delete_epim_orphaned_images'});
        customQueue.process();
    });

    $('#deleteProducts').on('click',function(){
        _o('Deleting Products..');
        $('.modal.deleteProducts').addClass('active');
        customQueue.reset();
        customQueue.queue(ajaxurl, {action: 'delete_products'});
        customQueue.process();
    });

    $('#ClearProducts').on('click',function (){
       if(confirm('Are you sure? This cannot be undone.')) {
           $(this).prop('disabled',true);
           $('.modal.ClearProducts').addClass('active');
           _o('Clearing all products, catagories and attributes from WooCommerce, please wait.....');
           customQueue.reset();
           customQueue.queue(ajaxurl,{action:'clear_woo_down'});
           customQueue.process();
       }
    });


    $('.custom_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    if($('#ePimTail').length) {
        let $sec = epim_ajax_object.security;
        $(function() {
            $.repeat(5000, function() {

                jQuery.ajax({
                    data:{security: $sec, action: 'cron_tail'},
                    type: "POST",
                    url: ajaxurl,
                    success: function (data) {
                        if(data != $('#ePimTail')) {
                            $('#ePimTail').append(data);
                            if ($('#ePimTail:hover').length == 0) {
                                $('#ePimTail').scrollTop($("#ePimTail")[0].scrollHeight);
                            }
                        }
                    }
                });

            });
        });
    }

    $('#WriteDiviCss').on('click',function (){
        let $sec = epim_ajax_object.security;
        let $primary = $('#epim_divi_primary_color').val();
        let $secondary = $('#epim_divi_secondary_color').val();
        jQuery.ajax({
            data:{security: $sec, action: 'divi_write_css_file', primary: $primary, secondary: $secondary},
            type: "POST",
            url: ajaxurl,
            success: function (data) {
                alert('Colours set to live');
            }
        });
    });

    $('#BuildDiviCategoryMenu').on('click',function (){
        let $sec = epim_ajax_object.security;
        let $epim_divi_number_menu_items = $('#epim_divi_number_menu_items').val();
        jQuery.ajax({
            data:{security: $sec, action: 'divi_build_category_menu', numItems: $epim_divi_number_menu_items},
            type: "POST",
            url: ajaxurl,
            dataType:'text',
            success: function (data) {
                alert('Category Menu Completed Successfully');
            },
            complete: function (data) {
                alert('Category Menu Completed');
            },
            error: function (data) {
                alert('Category Menu Completed Error');
            }
        });
    });

    if($('#GetCurrentUpdateData').length) {
        $('#GetCurrentUpdateData').click();
    }

    $('#epim_use_dynamic_data_sheets').click(function () {
        $('.visible-for-datasheets').toggleClass('revealed');
    });

    if(document.getElementById('epim_use_dynamic_data_sheets')) {
        if (document.getElementById('epim_use_dynamic_data_sheets').checked) {
            $('.visible-for-datasheets').toggleClass('revealed');
        }
    }
});