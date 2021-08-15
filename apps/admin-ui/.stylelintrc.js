const fabric = require("@umijs/fabric")

module.exports = {
  rules: {
    "at-rule-no-unknown": null
  },
  ...fabric.stylelint,
  ignoreFiles: ["src/assets/scss/**.scss"]
}
