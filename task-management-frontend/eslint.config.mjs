import { defineConfig } from "eslint/config";
import vue from "eslint-plugin-vue";
import typescriptEslint from "@typescript-eslint/eslint-plugin";
import globals from "globals";
import path from "node:path";
import { fileURLToPath } from "node:url";
import js from "@eslint/js";
import { FlatCompat } from "@eslint/eslintrc";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

export default defineConfig([{
    extends: compat.extends(
        "plugin:vue/vue3-recommended",
        "eslint:recommended",
        "plugin:@typescript-eslint/recommended",
        "prettier",
    ),

    plugins: {
        vue,
        "@typescript-eslint": typescriptEslint,
    },

    languageOptions: {
        globals: {
            ...globals.node,
        },

        ecmaVersion: 5,
        sourceType: "commonjs",

        parserOptions: {
            parser: "@typescript-eslint/parser",
        },
    },

    rules: {},
}]);