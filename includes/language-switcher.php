<div class="text-right p-4 bg-white shadow">
  <label for="languageSelect" class="mr-2 font-semibold">🌐 Language:</label>
  <select id="languageSelect" class="p-1 border rounded">
    <option value="en">English</option>
    <option value="hi">Hindi</option>
    <option value="mr">Marathi</option>
    <option value="kn">Kannada</option>
  </select>
</div>


<script>
  const translateApi = "https://libretranslate.de/translate";

  async function translatePage(toLang) {
    const elements = document.querySelectorAll("body *:not(script):not(style):not(select)");

    for (let el of elements) {
      if (el.childNodes.length === 1 && el.childNodes[0].nodeType === 3) {
        const originalText = el.innerText.trim();
        if (!originalText) continue;

        try {
          const response = await fetch(translateApi, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              q: originalText,
              source: "en",
              target: toLang,
              format: "text"
            })
          });

          const data = await response.json();
          if (data.translatedText) {
            el.innerText = data.translatedText;
          }
        } catch (error) {
          console.error("Translation error:", error);
        }
      }
    }
  }

  document.getElementById("languageSelect").addEventListener("change", (e) => {
    translatePage(e.target.value);
  });
</script>
















<!-- 


<div class="text-right p-4 bg-white shadow">
  <label for="languageRedirect" class="mr-2 font-semibold">🌐 Language:</label>
  <select id="languageRedirect" class="p-1 border rounded">
    <option value="">Select</option>
    <option value="en">English</option>
    <option value="hi">Hindi</option>
    <option value="mr">Marathi</option>
    <option value="kn">Kannada</option>
  </select>
</div>

<script>
  function isTranslatedByGoogle() {
    return window.location.hostname.includes("translate.google");
  }

  function getOriginalUrlFromTranslate() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("u") || window.location.origin;
  }

  function translatePage(lang, url) {
    const googleUrl = `https://translate.google.com/translate?hl=${lang}&sl=en&u=${encodeURIComponent(url)}`;
    window.location.href = googleUrl;
  }

  document.addEventListener("DOMContentLoaded", () => {
    const select = document.getElementById("languageRedirect");
    const pendingLang = sessionStorage.getItem("pendingLang");

    if (pendingLang) {
      sessionStorage.removeItem("pendingLang");
      translatePage(pendingLang, window.location.href);
    }

    select.addEventListener("change", () => {
      const selectedLang = select.value;
      if (!selectedLang) return;

      if (isTranslatedByGoogle()) {
        const originalUrl = getOriginalUrlFromTranslate();
        sessionStorage.setItem("pendingLang", selectedLang);
        window.location.href = originalUrl; // Go back, then auto retranslate
      } else {
        translatePage(selectedLang, window.location.href);
      }
    });
  });
</script> -->
