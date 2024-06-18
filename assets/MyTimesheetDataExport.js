addEventListener("DOMContentLoaded", () => {
    const createButton = (label, format) => {
      const button = document.createElement("button");
      button.classList.add("btn", "buttons-html5");
      button.innerHTML = label;
      button.name = "format";
      button.style.marginTop = "5px";
      button.value = format;
      button.formMethod = "GET";
      button.formAction = "/data_export/timesheets/my";

      return button;
    };
    const buttons = [
      createButton(
        leantime.i18n.__("dataexport.export_csv") ?? "Export (csv)",
        "csv",
      ),
      createButton(
        leantime.i18n.__("dataexport.export_xlsx") ?? "Export (xlsx)",
        "xlsx",
      ),
    ];

    // We want to add our buttons inside .dt-buttons inside #tableButtons, but that element may not exist yet.
    const wrapper = document.getElementById("timesheetList");
    if (wrapper) {
      const addButtons = (container) => {
        // Buttons are prepended to the container, so we process them in reverse order.
        for (const button of buttons.reverse()) {
          // Add a little spacing.
          container.append(document.createTextNode(" "));
          container.append(button);
        }
      };
      const container = wrapper.querySelector(".pull-left .padding-top-sm");
      if (null !== container) {
        addButtons(container);
      } else {
        // We don't yet have the target element, so we wait for it.
        const observer = new MutationObserver((mutationList, observer) => {
          for (const mutation of mutationList) {
            if (
              "childList" === mutation.type &&
              mutation.addedNodes.length > 0 &&
              mutation.addedNodes[0].matches(".pull-left")
            ) {
              addButtons(mutation.addedNodes[0]);
              observer.disconnect();
            }
          }
        });
        observer.observe(wrapper, { childList: true });
      }
    }
  });
