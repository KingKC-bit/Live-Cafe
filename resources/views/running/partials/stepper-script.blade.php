{{--
    The − / + control for extra runners or guests. Without JavaScript the box is a normal
    number field; with it, the box becomes read-only so the number can only move
    one step at a time, which stops typos like 30 instead of 3.
--}}
<script>
    document.querySelectorAll('[data-stepper]').forEach(function (stepper) {
        var input = stepper.querySelector('input');
        var buttons = stepper.querySelectorAll('[data-step]');

        input.readOnly = true;

        function limits() {
            return {
                min: input.min === '' ? 0 : Number(input.min),
                max: input.max === '' ? Infinity : Number(input.max),
            };
        }

        function refresh() {
            var value = Number(input.value || 0);
            var range = limits();

            buttons.forEach(function (button) {
                var step = Number(button.dataset.step);
                button.disabled = (step < 0 && value <= range.min) || (step > 0 && value >= range.max);
            });
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var range = limits();
                var next = Number(input.value || 0) + Number(button.dataset.step);

                input.value = Math.min(range.max, Math.max(range.min, next));
                refresh();
            });
        });

        refresh();
    });
</script>
