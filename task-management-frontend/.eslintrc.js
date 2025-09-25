module.exports = {
    root: true,
    env: {
      node: true,
    },
    extends: [
      '@nuxt/eslint-config', // for Nuxt projects
      'plugin:vue/vue3-recommended',
      'plugin:@typescript-eslint/recommended', // Only if using TypeScript
      'prettier',
    ],
    plugins: ['vue', '@typescript-eslint', 'jsdoc', 'prettier'],
    rules: {
      "jsdoc/require-description": "error",
      "jsdoc/check-values": "error",
    },
    parserOptions: {
      parser: '@typescript-eslint/parser',
    },
  };