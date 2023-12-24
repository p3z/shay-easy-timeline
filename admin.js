( () => {
        let create_timeline_form = document.querySelector('.pws-create-timeline-form');
        let create_form_toggle = document.querySelector('.js-toggle-create-form');
        let create_form_state = 0;

        create_form_toggle.onclick = () => {

                if( create_form_state === 0 ){
                        create_timeline_form.classList.remove('hide');
                        create_form_toggle.textContent = "Hide new timeline";
                        create_form_state = 1;
                } else {
                        create_timeline_form.classList.add('hide');
                        create_form_toggle.textContent = "Create new timeline";
                        create_form_state = 0;
                }


        }
})()