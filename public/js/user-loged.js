jQuery(document).ready(function ($) {


    if (document.querySelector('.yith-wcbk-date-picker-wrapper')) {

        setTimeout(() => {
            document.querySelector('.yith-wcbk-date-picker-wrapper').addEventListener('click', () => {

                if ($('#roast_custom_options').val() == 'Pristatyti į namus') {
                    const arrow = document.querySelector('body')
                    arrow.addEventListener('click', () => {
                        $('.ui-datepicker-today').addClass('ui-datepicker-unselectable ui-state-disabled bk-non-available-date');
                        const getTime = new Date();
                        //  console.log(getTime.getDate());

                        const allDays = document.querySelectorAll('.ui-datepicker-calendar td');
                        let dday = '';
                        let disabledDay = '';
                        let uzd = false;

                        allDays.forEach((val, dayInd) => {
                            // console.log(val.textContent);

                            // if(!uzd){
                            if (val.textContent == getTime.getDate()) {
                                if (!val.classList.contains('ui-state-disabled')) {
                                    dday = +val.textContent + 2;
                                    disabledDay = +val.textContent + 1;

                                    return;
                                }
                                dday = +val.textContent + 3;
                                disabledDay = +val.textContent + 1;
                            }

                            // console.log(+dday, val.textContent);

                            if (+dday === +val.textContent) {
                                allDays.forEach((val, i) => {
                                    val.classList.remove('ui-datepicker-current-day');
                                    if (val.firstElementChild) val.firstElementChild.classList.remove('ui-state-active');
                                });
                                if (val.firstElementChild) {
                                    val.firstElementChild.classList.add('ui-state-active');
                                    uzd = true;
                                }
                            }

                            if (+disabledDay === +val.textContent) {
                                val.setAttribute('class', 'ui-datepicker-week-end ui-datepicker-unselectable ui-state-disabled bk-non-available-date');
                            }
                        });


                    })

                    $('.ui-datepicker-today').addClass('ui-datepicker-unselectable ui-state-disabled bk-non-available-date');
                    console.log('iki cia veikia');
                    const getTime = new Date();
                    //  console.log(getTime.getDate());

                    const allDays = document.querySelectorAll('.ui-datepicker-calendar td');
                    let dday = '';
                    let disabledDay = '';
                    let uzd = false;

                    allDays.forEach((val, dayInd) => {
                        // console.log(val.textContent);

                        // if(!uzd){
                        if (val.textContent == getTime.getDate()) {
                            if (!val.classList.contains('ui-state-disabled')) {
                                dday = +val.textContent + 2;
                                disabledDay = +val.textContent + 1;

                                return;
                            }
                            dday = +val.textContent + 3;
                            disabledDay = +val.textContent + 1;
                        }

                        // console.log(+dday, val.textContent);

                        if (+dday === +val.textContent) {
                            allDays.forEach((val, i) => {
                                val.classList.remove('ui-datepicker-current-day');
                                if (val.firstElementChild) val.firstElementChild.classList.remove('ui-state-active');
                            });
                            if (val.firstElementChild) {
                                val.firstElementChild.classList.add('ui-state-active');
                                uzd = true;
                            }
                        }

                        if (+disabledDay === +val.textContent) {
                            val.setAttribute('class', 'ui-datepicker-week-end ui-datepicker-unselectable ui-state-disabled bk-non-available-date');
                        }
                    });
                }




            });
        }, 500);

    }

});