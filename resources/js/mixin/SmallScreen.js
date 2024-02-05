export default {
    computed: {
        xsmallScreen() {
            return this.$q.screen.lt.sm
        },
        smallScreen() {
            return this.$q.screen.lt.md
        },
        xssmallScreen() {
            return this.$q.screen.xs
        }
    }
}
