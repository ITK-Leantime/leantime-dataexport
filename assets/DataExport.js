addEventListener('DOMContentLoaded', () => {
    const createButton = (label, format) => {
        const button = document.createElement('button');
        button.classList.add('dt-button', 'buttons-html5')
        button.innerHTML = label;
        button.name = 'format';
        button.value = format;
        button.formMethod = 'GET'
        button.formAction = '/data_export/timesheets/all'

        return button
    }
    const buttons = [
        createButton(leantime.i18n.__('dataexport.export_csv') ?? 'Export (csv)', 'csv'),
        createButton(leantime.i18n.__('dataexport.export_xlsx') ?? 'Export (xlsx)', 'xlsx')
    ]

    // We want to add our buttons inside .dt-buttons inside #tableButtons, but that element may not exist yet.
    const wrapper = document.getElementById('tableButtons')
    if (wrapper) {
        const addButtons = (container) => {
            // Buttons are prepended to the container, so we process them in reverse order.
            for (const button of buttons.reverse()) {
                // Add a little spacing.
                container.prepend(document.createTextNode(' '))
                container.prepend(button)
            }
        }
        const container = wrapper.querySelector('.dt-buttons')
        if (null !== container) {
            addButtons(container)
        } else {
            // We don't yat have the target element, so we wait for it.
            const observer = new MutationObserver((mutationList, observer) => {
                for (const mutation of mutationList) {
                    if ('childList' === mutation.type
                        && mutation.addedNodes.length > 0
                        && mutation.addedNodes[0].matches('.dt-buttons')) {
                        addButtons(mutation.addedNodes[0])
                        observer.disconnect()
                    }
                }
            })
            observer.observe(wrapper, {childList: true});
        }
    }
})
