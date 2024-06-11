import { createI18n } from 'vue-i18n';

const messages = {
    en: require('../lang/en/vue.json'),
    es: require('../lang/es/vue.json'),
};

const locale = document.querySelector('meta[name="locale"]').content || 'en';

const i18n = createI18n({
    locale,
    fallbackLocale: locale,
    messages,
});

export default i18n;
