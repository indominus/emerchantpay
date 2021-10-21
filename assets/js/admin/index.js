import $ from 'jquery';
import '../../scss/common.scss';

$(document).ready(function () {
    'use strict';
    $('.btn-delete').on('click', function (e) {
        e.preventDefault();
        if (window.confirm('Are you sure')) {
            $.ajax({method: 'DELETE', url: $(this).data('url')}).done(function () {
                window.location.reload();
            });
        }
    })
});
