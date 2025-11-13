export function trans(langKey, replace = {}) {
    let line = window.Veles.langs[langKey];

    for (let key in replace) {
        line = line.replace(`:${key}`, replace[key]);
    }

    return line;
}

export function formatCurrency(amount) {
    // Önce sayı formatını al (para birimi sembolü olmadan)
    const numberFormat = new Intl.NumberFormat(Veles.locale.replace("_", "-"), {
        ...(Veles.locale === "ar" && {
            numberingSystem: "arab",
        }),
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
    
    // Para birimi sembolünü sağa ekle
    const currencySymbol = new Intl.NumberFormat(Veles.locale.replace("_", "-"), {
        style: "currency",
        currency: Veles.currency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(0).replace(/[\d.,\s]/g, ''); // Sadece para birimi sembolünü al
    
    return `${numberFormat} ${currencySymbol}`;
}
