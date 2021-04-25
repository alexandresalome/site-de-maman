import { Controller } from 'stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        const zoom = this.element.getAttribute('data-zoom');
        const title = this.element.getAttribute('title');

        const modal = document.getElementById('modal');
        const modalBody = document.getElementById('modal-body');
        const modalClose = document.getElementById('modal-close');
        const modalTitle = document.getElementById('modal-title');

        if (!modal || !modalBody || !modalClose || !modalTitle) {
            console.log('Missing one of the <div> elements required for modals:', modal, modalBody, modalClose, modalTitle);
        }

        modalTitle.innerText = title;

        const html = '<img class="img-fluid" src="' + zoom + '" alt="Agrandissement" />';

        this.element.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            modalBody.innerHTML = html;
            modal.setAttribute('class', 'modal fade show')
            modalClose.addEventListener('click', (event) => {
                modal.setAttribute('class', 'modal fade');
                modalBody.innerHTML = '';
            });
        });
    }
}
