/** @type {import("prettier").Config} */
export default {
    semi: true,
    singleQuote: true,
    singleAttributePerLine: false,
    htmlWhitespaceSensitivity: 'css',
    printWidth: 150,
    plugins: [
        'prettier-plugin-organize-imports',
        'prettier-plugin-tailwindcss',
    ],
    tailwindFunctions: ['clsx', 'cn'],
    tabWidth: 4,
    overrides: [
        {
            files: '**/*.yml',
            options: {
                tabWidth: 2,
            },
        },
    ],
};
