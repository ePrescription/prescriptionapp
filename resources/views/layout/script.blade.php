<?php /* ?>
<!--jQuery-->

{!!  Html::script(asset('js/jquery-1.11.3.min.js')) !!}
<!--Bootstrap-->
{!!  Html::script(asset('css/bootstrap/js/bootstrap.min.js')) !!}
<!--Date Picker-->
{!!  Html::script(asset('js/datepicker/bootstrap-datepicker.min.js')) !!}
<!-- Flex Slider-->
{!!  Html::script(asset('flexslider/js/jquery.flexslider-min.js')) !!}
{!!  Html::script(asset('js/script.js')) !!}
<?php */ ?>

{!!  Html::script(asset('js/jquery.js')) !!}
{!!  Html::script(asset('js/plugins.js')) !!}
<?php /* ?>
<script>
    function timer() {
        if (animator.curPercentage < animator.targetPercentage) {
            animator.curPercentage += 1;
        } else if (animator.curPercentage > animator.targetPercentage) {
            animator.curPercentage -= 1;
        }

        $(animator.outputSelector).text(animator.curPercentage + "%");

        if (animator.curPercentage != animator.targetPercentage) {
            setTimeout(timer, animator.animationSpeed)
        }
    }

    function PercentageAnimator() {
        this.animationSpeed = 50;
        this.curPercentage = 0;
        this.targetPercentage = 0;
        this.outputSelector = ".countPercentage";

        this.animate = function(percentage) {
            this.targetPercentage = percentage;
            setTimeout(timer, this.animationSpeed);
        }
    }

    var animator = new PercentageAnimator();
    animator.curPercentage = 0;
    animator.animate(100);
</script>
<?php */ ?>
@yield('scripts')
