{!!  Html::script(asset('plugins/jQuery/jQuery-2.1.4.min.js')) !!}
{!!  Html::script(asset('bootstrap/js/bootstrap.min.js')) !!}
{!!  Html::script(asset('plugins/iCheck/icheck.min.js')) !!}

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
@yield('scripts')
