const APPLICATION_ID = square.application_id
const LOCATION_ID = square.location_id

function uuidv4() {
  return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
    var r = Math.random() * 16 | 0, v = c == "x" ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

async function createPayment(token, args) {

  const { total, wp_nonce, currency, description, form_unique } = args;

  const body = new URLSearchParams({
    action: "payment_request",
    wp_nonce,
    idempotency_key: uuidv4(),
    source_id: token,
    total,
    currency,
    description,
  })

  try {


    const response = await fetch(wpbs_ajax.ajax_url, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "Cache-Control": "no-cache"
      },
      body
    })
    if (!response.ok) {
      return response.json().then(
        errorInfo => Promise.reject(errorInfo));
    }
    const data = await response.json();

    if (!data.success) {
      jQuery(`.wpbs-square-payment-confirmation-inner-${form_unique}`).show();
      jQuery("#wpbs-processing").remove();
      jQuery(".wpbs-payment-confirmation-square-form").prepend("<p class=\"square-error\">" + data.error + "</p>");
      return;
    }

    jQuery(`.wpbs-square-payment-confirmation-${form_unique}`).parents(".wpbs-main-wrapper").find(".wpbs-calendar").append("<div class=\"wpbs-overlay\"><div class=\"wpbs-overlay-spinner\"><div class=\"wpbs-overlay-bounce1\"></div><div class=\"wpbs-overlay-bounce2\"></div><div class=\"wpbs-overlay-bounce3\"></div></div></div>");
    jQuery(`.wpbs-square-payment-confirmation-${form_unique}`).parents(".wpbs-container").addClass("wpbs-is-loading");
    jQuery(`.wpbs-square-payment-confirmation-${form_unique} form`).append("<input type=\"hidden\" name=\"wpbs-square-payment-id\" value=\"" + data.id + "\" />")
    jQuery(`.wpbs-square-payment-confirmation-${form_unique} form`).submit();

  } catch (err) {
    console.error(err);

    jQuery(`.wpbs-square-payment-confirmation-inner-${form_unique}`).show();
    jQuery(`.wpbs-square-payment-confirmation-${form_unique}`).remove();
  }


}

async function tokenize(paymentMethod) {
  const tokenResult = await paymentMethod.tokenize();
  if (tokenResult.status === 'OK') {
    return tokenResult.token;
  } else {
    let errorMessage = `Tokenization failed-status: ${tokenResult.status}`;
    if (tokenResult.errors) {
      errorMessage += ` and errors: ${JSON.stringify(
        tokenResult.errors
      )}`;
    }
    throw new Error(errorMessage);
  }
}

async function initializeCard(payments) {
  const card = await payments.card();
  await card.attach('#card-container');
  return card;
}


async function main(args) {
  if (!window.Square) {
    throw new Error("Something went wrong intializing Square")
  }

  const payments = window.Square.payments(APPLICATION_ID, LOCATION_ID)
  const card = await initializeCard(payments);


  async function handlePaymentMethodSubmission(event, paymentMethod) {
    event.preventDefault()
    try {
      cardButton.disabled = true;

      const token = await tokenize(paymentMethod);
      const paymentResults = await createPayment(token, args);
      console.debug(paymentResults)
      cardButton.disabled = false;
    } catch (e) {
      cardButton.disabled = false;

      console.error(e)
    }
  }
  const cardButton = document.getElementById("card-button")

  if (cardButton) {
    cardButton.addEventListener("click", async function (event) {
      await handlePaymentMethodSubmission(event, card);
    })
  }
}

