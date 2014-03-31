UserManagement = {
    options: {
        url_register      : '/auth/register',
        url_signin        : '/auth/signin',
        url_logout        : '/auth/logout',
        url_list_mappings : '/user/show_mappings'
    },

    initialise: function() {
        $('div#registration-dialog button.btn-register').on('click', function(evt) {
            UserManagement.register();
        });

        $('div#sign-in-dialog button.btn-signin').on('click', function(evt) {
            UserManagement.signin();
        });

        $('div.navbar ul.nav a.btn-logout').on('click', function(evt) {
            console.log('logout');
            UserManagement.logout();
        });

        // Mappings listing
        $('div.navbar ul.nav a#btn-show-mappings-dlg').on('click', function(e) {
            e.preventDefault();

            // Set the export type
            UserManagement.showMappings();
        });
    },

    showMappings: function() {
        $('div#mappings-list-dialog div.modal-body').empty();
        $('div#mappings-list-dialog div.modal-body').html('<div class="loader">Loading...</div>');

        // Process serialised form data via server side
        $.ajax({
            type: "GET",
            cache: false,
            url: UserManagement.options.url_list_mappings,
            success: function(html) {
                $('div#mappings-list-dialog div.modal-body').html(html);
            },
            dataType: 'html'
        });
    },

    logout: function() {
        $.ajax({
            type: "GET",
            cache: false,
            url: UserManagement.options.url_logout,
            success: function(data) {
                if (data === true) {
                    location.reload();
                }
            },
            dataType: 'json'
        });
    },

    register: function() {
        var msg_area   = $('div#registration-dialog div.message-area');
        var error_msg  = 'Snap!';
        var error      = false;

        // Input values
        var user_email           = $('div#registration-dialog input.email-input');
        var user_name            = $('div#registration-dialog input.name-input');
        var user_password        = $('div#registration-dialog input.password-input');
        var user_verify_password = $('div#registration-dialog input.password-verify-input');

        // Passwords missmatch
        var email_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!email_regex.test(user_email.val())) {
            error_msg += '<br>* Incorrect email address.';
            // Hightlight the field
            user_email.parents('.form-group').addClass('has-warning');
            error = true;
        } else {
            user_email.parents('.form-group').removeClass('has-warning');
        }

        // Invalid email
        if (user_password.val() !== user_verify_password.val() || user_password.val().length <= 5) {
            error_msg += '<br>* Passwords mismatch or too short(5 chars minimum). please recheck passwords and enter again.';
            // Hightlight field
            user_password.parents('.form-group').addClass('has-warning');
            user_verify_password.parents('.form-group').addClass('has-warning');
            error = true;
        } else {
            user_password.parents('.form-group').removeClass('has-warning');
            user_verify_password.parents('.form-group').removeClass('has-warning');
        }

        if (error) {
            msg_area
                .empty()
                .html('<div class="alert alert-warning">' + error_msg + '</div>')
                .delay(5000)
                .fadeOut(500)
                .promise()
                .done( function() {
                    msg_area
                        .empty()
                        .show();
                });

            return false;
        }

        $.ajax({
            type: "POST",
            cache: false,
            url: UserManagement.options.url_register,
            data: {
                name:     user_name.val(),
                email:    user_email.val(),
                password: user_password.val(),
            },
            success: function(data) {
                // Remove all field containers (name, email, passwords blah)
                $('div#registration-dialog div.modal-body div.form-group').remove();

                msg_area
                    .empty()
                    .html('<div class="alert alert-success">' + data.message + '</div>')
                    .delay(5000)
                    .fadeOut(500)
                    .promise()
                    .done( function() {
                        window.location = '/auth/signin/' + data.id
                    });
            },
            dataType: 'json'
        });
    },

    signin: function() {
        var msg_area   = $('div#sign-in-dialog div.message-area');

        // Input values
        var user_email    = $('div#sign-in-dialog input.email-input');
        var user_password = $('div#sign-in-dialog input.password-input');

        $.ajax({
            type: "POST",
            cache: false,
            url: UserManagement.options.url_signin,
            data: {
                email:    user_email.val(),
                password: user_password.val()
            },
            success: function(data) {
                if (data === true) {
                    location.reload();
                } else {
                    msg_area
                        .empty()
                        .html('<div class="alert alert-danger">Incorrect login attempt, please recheck your details.</div>');
                }
            },
            dataType: 'json'
        });
    }
};

DataGrid = {
    options: {
        element : '',
        table   : null
    },

    initialise: function(table) {
        this.options.element = table;
    },

    ajaxGetFileData: function (file_id) {

        // Load CSV data
        this.options.table = this.options.element.dataTable( {
            "sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
            "bProcessing": true,
            "sScrollX": "100%",
            "bAutoWidth": true,
            "sPaginationType": 'bs_full',
            "sAjaxSource": '/view/get_file_content/' + file_id,
        });
    }
};

Mapping = {

    initialise: function() {

        // Bind add new column button (Map)
        $(document.body).on('click', 'a.btn-add-column', function(e) {
            e.preventDefault();

            var element = $(e.target);
            Mapping.addColumn(element);
        });

        // Remove "X" of an added button
        $(document).on('click', 'button.btn-remove-column, button.btn-remove-column > span ', function(e) {
            e.preventDefault();

            var element = $(e.target);
            Mapping.removeColumn(element);
        });

    },

    // Add merge/map column
    addColumn: function(element) {
        var column_name = element.data('column');
        var column_index = MappingGroup.getClosestColumnGroupIndex(element);

        var $column_tr  = $('<tr>' +
                            '<td>' + column_name + '</td>' +
                            '<td><input data-name="column[][' + column_name +']" type="hidden" value="' + column_name + '"></td>' +
                            '<td>' + '<div class="col-sm-11 no-padding"><input data-name="column_separator[][' + column_name +']" type="text" class="form-control input-sm"></div>' + '</td>' +
                            // '<td>' + '<div class="col-sm-11 no-padding"><input data-name="column_stripper[][' + column_name +']" type="text" class="form-control input-sm"></div>' + '</td>' +
                            '<td>' + '<button type="button" class="btn btn-danger btn-xs btn-remove-column"> <span class="glyphicon glyphicon-remove"></span> Remove</button>' + '</td>' +
                            '</tr>');

        // Remove when-empty tr before adding the field
        element.parents('div.form-horizontal').children('table.columns-container').find('tbody tr.when-empty').remove();
        element.parents('div.form-horizontal').children('table.columns-container').find('tbody').append($column_tr);
    },

    // Remove merge/map column added
    removeColumn: function(element) {
        element.parents('tr').remove();
    },

};

MappingGroup = {
    options: {
        url_save_mappings : '/user/save_mappings'
    },

    initialise: function() {

        // Add group
        $(document.body).on('click', 'button.btn-add-group-mapping', function(e) {
            e.preventDefault();

            var element = $(e.target);
            MappingGroup.addGroup(element);
        });

        // Remove group
        $(document.body).on('click', 'button.btn-remove-group-mapping, button.btn-remove-group-mapping > span ', function(e) {

            var element = $(e.target);
            MappingGroup.removeGroup(element);
        });

        $('div#save-dialog button.btn-save-mappings').on('click', function() {
            MappingGroup.save();
        });

        // Add the very first column group to start off with...
        MappingGroup.addGroup(null);
    },

    getClosestColumnGroupIndex: function(element) {
        return $('div.merge-column-container').index(element.parents('div.merge-column-container'));
    },

    // Add merge column group
    addGroup: function(element) {
        var merge_column_container = $($('script#column-group-markup:first').html());

        if (element === null) {
            $('form.merge-column-group').append(merge_column_container);
        } else {
            element.parents('div.merge-column-container').after(merge_column_container).promise().done(function() {

                // Scroll to view the newly added column group
                $('html,body').animate({scrollTop: merge_column_container.offset().top}, 500);
            });
        }

    },

    // Remove merge/map column group
    removeGroup: function(element) {
        element.parents('div.merge-column-container').remove();
    },

    save: function() {
        var jq_column_groups_container = $('form.merge-column-group');
        var jq_save_name_element       = $('div#save-dialog input.mapping-name-input');
        var jq_des_name_element        = $('div#save-dialog input.mapping-des-input');

        // TODO: Dont let the user save without a name

        jq_column_groups_container.children('div.merge-column-container').each(function(i, merge_column_container) {
            $(merge_column_container).find('*[data-name]').each(function(j, element) {
                var jq_element          = $(element);
                var el_name             = jq_element.data('name');
                var el_name_conditioned = el_name.replace(/^(.*)(\[\])(\[.*\])?$/, '$1[' + i + ']$3');

                jq_element.attr('name', el_name_conditioned);
            });

        }).promise().done(function() {
            var json_encoded = jq_column_groups_container.serializeObject();

            json_encoded.mapping_name = jq_save_name_element.val();
            json_encoded.description  = jq_des_name_element.val();
            // Process serialised form data via server side
            $.ajax({
                type: "POST",
                cache: false,
                url: MappingGroup.options.url_save_mappings,
                data: json_encoded,
                success: function(html) {
                    $('div#preview-table div.modal-body').html(html);
                },
                dataType: 'html'
            });
        });
    }

};

Exporter = {
    options: {
        url_process : '/process/merge_fields/',
        url_preview : '/process/preview/',
        file_id     : null
    },

    initialise: function() {

        // Export
        $('nav a.export-type').on('click', function(e) {
            e.preventDefault();

            // Set the export type

            Exporter.process(null);
        });

        // Preview
        $('nav.navbar a#btn-preview-table').on('click', function(e) {
            e.preventDefault();

            // Set the export type
            Exporter.preview();
        });
    },

    //
    // Go through elements found on the page and update their names [array] ids
    // as per columns added by the uesr.
    //
    process: function(limit) {

        var jq_column_groups_container = $('form.merge-column-group');

        jq_column_groups_container.children('div.merge-column-container').each(function(i, merge_column_container) {
            $(merge_column_container).find('*[data-name]').each(function(j, element) {
                var jq_element          = $(element);
                var el_name             = jq_element.data('name');
                var el_name_conditioned = el_name.replace(/^(.*)(\[\])(\[.*\])?$/, '$1[' + i + ']$3');

                jq_element.attr('name', el_name_conditioned);
            });

        }).promise().done(function() {
            var json_encoded = jq_column_groups_container.serializeObject();

            // Process serialised form data via server side
            $.ajax({
                type: "POST",
                cache: false,
                url: Exporter.options.url_process + Exporter.options.file_id + '/' + limit,
                data: json_encoded,
                success: function(data) {
                    console.log(data);
                },
                dataType: 'json'
            });
        });

    },

    preview: function() {
        var jq_column_groups_container = $('form.merge-column-group');
        var limit = 10;

        $('div#preview-table div.modal-body').empty();
        $('div#preview-table div.modal-body').html('<div class="loader">Processing...</div>');

        jq_column_groups_container.children('div.merge-column-container').each(function(i, merge_column_container) {
            $(merge_column_container).find('*[data-name]').each(function(j, element) {
                var jq_element          = $(element);
                var el_name             = jq_element.data('name');
                var el_name_conditioned = el_name.replace(/^(.*)(\[\])(\[.*\])?$/, '$1[' + i + ']$3');

                jq_element.attr('name', el_name_conditioned);
            });

        }).promise().done(function() {
            var json_encoded = jq_column_groups_container.serializeObject();

            // Process serialised form data via server side
            $.ajax({
                type: "POST",
                cache: false,
                url: Exporter.options.url_preview + Exporter.options.file_id + '/' + limit,
                data: json_encoded,
                success: function(html) {
                    $('div#preview-table div.modal-body').html(html);
                },
                dataType: 'html'
            });
        });

    }
};

$(document).ready(function() {
    var tableGrid = $('table#table_id');

    // User manager
    UserManagement.initialise();

    // Initialise table for viewing CSV data if exists
    if (tableGrid.length) {

        // Pull dataset from the server
        DataGrid.initialise(tableGrid);
        DataGrid.ajaxGetFileData(tableGrid.data('file-id'));

        // Set file_id in exporter class
        Exporter.options.file_id = tableGrid.data('file-id');

        // Initialise Mapping class
        Mapping.initialise();

        // Initialise Mapping Group class
        MappingGroup.initialise();

        // Initialise exporter
        Exporter.initialise();
    }

});
