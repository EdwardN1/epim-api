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
        if(action==='get_all_changed_products_since') {
            if( data!='[]' ) {
                //window.console.log(data);
                if ($.trim(data)) {
                    let products = JSON.parse(data);
                    let c = 0;
                    $(products).each(function (index, product) {
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
                obj.queue(ajaxurl,{action: 'create_category', ID: record.Id, name: record.Name, ParentID: record.ParentId, picture_ids: record.PictureIds});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
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

                    obj.queue(ajaxurl,{action: 'import_picture', ID: picture.Id, weblink: picture.WebPath});
                })

            }
        }
    });

    let customQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.deleteAttributes').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
    });

    let branchesQueue = new ts_execute_queue('#ePimResult', function () {
        _o('finished');
        $('.modal.CreateBranches').removeClass('active');
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
        $('.modal.CreateBranches').removeClass('active');
    }, function (action, request, data) {
        _o('Action Completed: ' + action);
        _o('Request: ' + request);
        _o('<br>Data: ' + data);
        window.console.log(action);
        if(action==='get_deleted_entities_count') {
            let deletedEntitiesCount = JSON.parse(data);
            let obj = this;
            let c = 0;
            $(deletedEntitiesCount).each(function (index, record) {

                obj.queue(ajaxurl,{action: 'get_deleted_entities_variations', TotalResults: record.TotalResults});
                if(debug) {
                    c++;
                    if (c >= cMax) {
                        return false;
                    }
                }
            });
        }
        if(action==='get_deleted_entities_variations') {
            let variations = JSON.parse(data);
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

    $('#deleteAttributes').on('click',function(){
        _o('Deleting Attributes..');
        $('.modal.deleteAttributes').addClass('active');
        customQueue.reset();
        customQueue.queue(ajaxurl, {action: 'delete_attributes'});
        customQueue.process();
    });


    $('.custom_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

})
;