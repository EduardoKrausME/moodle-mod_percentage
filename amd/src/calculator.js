// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * calculator.js
 *
 * @package   mod_percentage
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(["jquery", "core/str"], function($, Str) {
    "use strict";

    const state = {
        operation: null,
        result: null,
        visualization: "auto",
        options: {},
        strings: {},
    };

    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

    const number = (root, field) => {
        const form = root.find(`[data-operation-form="${state.operation}"]`);
        const raw = form.find(`[data-field="${field}"]`).val();
        const value = Number(String(raw).replace(",", "."));
        return Number.isFinite(value) ? value : null;
    };

    const formatNumber = (value) => {
        return Number(value).toLocaleString(undefined, {
            minimumFractionDigits: 0,
            maximumFractionDigits: state.options.decimalplaces,
        });
    };

    const formatPercent = (value) => `${formatNumber(value)}%`;

    const escapeHtml = (value) => $("<div>").text(String(value)).html();

    const setOperation = (root, operation) => {
        state.operation = operation;
        root.find("[data-operation-form]").prop("hidden", true);
        root.find(`[data-operation-form="${operation}"]`).prop("hidden", false);
        root.find("[data-action='select-operation']").each(function() {
            const active = $(this).data("operation") === operation;
            $(this).toggleClass("is-active", active).attr("aria-pressed", active ? "true" : "false");
        });
        clearResult(root);
    };

    const clearResult = (root) => {
        state.result = null;
        root.find("[data-region='result']").prop("hidden", true);
        root.find("[data-region='empty-result']").prop("hidden", false);
        root.find("[data-region='visual-panel']").prop("hidden", true);
    };

    const errorResult = (root, message) => {
        root.find("[data-region='empty-result']").prop("hidden", true);
        root.find("[data-region='result']").prop("hidden", false);
        root.find("[data-region='result-value']").text(message);
        root.find("[data-region='result-secondary'], [data-region='formula'], [data-region='explanation']").empty();
        root.find("[data-region='visual-panel']").prop("hidden", true);
    };

    const calculate = (root) => {
        let result = null;

        if (state.operation === "percentageof") {
            const percentage = number(root, "percentage");
            const value = number(root, "value");
            if (percentage === null || value === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            const answer = (percentage / 100) * value;
            result = {
                primary: formatNumber(answer),
                secondary: `${formatPercent(percentage)} × ${formatNumber(value)}`,
                formula: `${formatNumber(percentage)} ÷ 100 × ${formatNumber(value)} = ${formatNumber(answer)}`,
                explanation: state.strings.explanation_percentageof,
                percent: percentage,
                base: value,
                answer: answer,
                auto: "grid",
            };
        } else if (state.operation === "whatpercentage") {
            const part = number(root, "part");
            const total = number(root, "total");
            if (part === null || total === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            if (total === 0) {
                errorResult(root, state.strings.cannotdividebyzero);
                return;
            }
            const percentage = (part / total) * 100;
            result = {
                primary: formatPercent(percentage),
                secondary: state.strings.result_whatpercentage,
                formula: `${formatNumber(part)} ÷ ${formatNumber(total)} × 100 = ${formatPercent(percentage)}`,
                explanation: state.strings.explanation_whatpercentage,
                percent: percentage,
                base: total,
                answer: part,
                auto: "donut",
            };
        } else if (state.operation === "change") {
            const initial = number(root, "initial");
            const final = number(root, "final");
            if (initial === null || final === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            if (initial === 0) {
                errorResult(root, state.strings.cannotdividebyzero);
                return;
            }
            const difference = final - initial;
            const percentage = (difference / Math.abs(initial)) * 100;
            const direction = difference >= 0 ? state.strings.increase_label : state.strings.decrease_label;
            result = {
                primary: `${difference >= 0 ? "+" : ""}${formatPercent(percentage)}`,
                secondary: `${direction}: ${difference >= 0 ? "+" : ""}${formatNumber(difference)}`,
                formula: `(${formatNumber(final)} − ${formatNumber(initial)}) ÷ ${formatNumber(Math.abs(initial))} × 100 = ${formatPercent(percentage)}`,
                explanation: state.strings.result_change,
                percent: percentage,
                initial: initial,
                final: final,
                auto: "comparison",
            };
        } else if (state.operation === "increase") {
            const base = number(root, "base");
            const percentage = number(root, "percentage");
            if (base === null || percentage === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            const addition = base * percentage / 100;
            const final = base + addition;
            result = {
                primary: formatNumber(final),
                secondary: `${state.strings.increase_label}: +${formatNumber(addition)}`,
                formula: `${formatNumber(base)} + (${formatNumber(base)} × ${formatNumber(percentage)} ÷ 100) = ${formatNumber(final)}`,
                explanation: state.strings.result_increase,
                percent: percentage,
                initial: base,
                final: final,
                auto: "comparison",
            };
        } else if (state.operation === "discount") {
            const base = number(root, "base");
            const percentage = number(root, "percentage");
            if (base === null || percentage === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            const discount = base * percentage / 100;
            const final = base - discount;
            result = {
                primary: formatNumber(final),
                secondary: `${state.strings.decrease_label}: -${formatNumber(discount)}`,
                formula: `${formatNumber(base)} − (${formatNumber(base)} × ${formatNumber(percentage)} ÷ 100) = ${formatNumber(final)}`,
                explanation: state.strings.result_discount,
                percent: percentage,
                initial: base,
                final: final,
                auto: "bar",
            };
        } else if (state.operation === "margin") {
            const cost = number(root, "cost");
            const sale = number(root, "sale");
            if (cost === null || sale === null) {
                errorResult(root, state.strings.invalidnumber);
                return;
            }
            if (sale === 0 || cost === 0) {
                errorResult(root, state.strings.cannotdividebyzero);
                return;
            }
            const profit = sale - cost;
            const margin = (profit / sale) * 100;
            const markup = (profit / cost) * 100;
            result = {
                primary: `${state.strings.result_margin}: ${formatPercent(margin)}`,
                secondary: `${state.strings.result_markup}: ${formatPercent(markup)} · ${state.strings.result_profit}: ${formatNumber(profit)}`,
                formula: `(${formatNumber(sale)} − ${formatNumber(cost)}) ÷ ${formatNumber(sale)} × 100 = ${formatPercent(margin)}`,
                explanation: state.strings.explanation_margin,
                percent: margin,
                cost: cost,
                sale: sale,
                profit: profit,
                markup: markup,
                auto: "bar",
                composition: true,
            };
        }

        state.result = result;
        renderResult(root);
    };

    const renderResult = (root) => {
        const result = state.result;
        if (!result) {
            return;
        }

        root.find("[data-region='empty-result']").prop("hidden", true);
        root.find("[data-region='result']").prop("hidden", false);
        root.find("[data-region='result-value']").text(result.primary);
        root.find("[data-region='result-secondary']").text(result.secondary || "");
        root.find("[data-region='formula']").text(state.options.showformula ? result.formula : "").toggle(state.options.showformula);
        root.find("[data-region='explanation']").text(state.options.showexplanation ? result.explanation : "").toggle(state.options.showexplanation);
        root.find("[data-region='visual-panel']").prop("hidden", false);

        const selected = state.visualization === "auto" ? result.auto : state.visualization;
        renderVisual(root, selected);
    };

    const renderVisual = (root, visualization) => {
        if (!state.result) {
            return;
        }

        root.find("[data-action='change-visualization']").removeClass("is-active")
            .filter(`[data-visualization="${visualization}"]`).addClass("is-active");

        const visual = root.find("[data-region='visual']");
        if (visualization === "donut") {
            visual.html(donutHtml(state.result));
        } else if (visualization === "grid") {
            visual.html(gridHtml(state.result));
        } else if (visualization === "comparison") {
            visual.html(comparisonHtml(state.result));
        } else {
            visual.html(barHtml(state.result));
        }
    };

    const barHtml = (result) => {
        if (result.composition) {
            const costPercent = result.sale === 0 ? 0 : clamp(result.cost / result.sale * 100, 0, 100);
            const profitPercent = clamp(100 - costPercent, 0, 100);
            return `<div class="percentage-composition">
                <div class="percentage-composition-bar" role="img" aria-label="${escapeHtml(formatPercent(result.percent))}">
                    <span class="percentage-composition-cost" style="width:${costPercent}%"></span>
                    <span class="percentage-composition-profit" style="width:${profitPercent}%"></span>
                </div>
                <div class="percentage-composition-legend">
                    <span>${escapeHtml(state.strings.cost_label)}: ${escapeHtml(formatNumber(result.cost))}</span>
                    <span>${escapeHtml(state.strings.profit_label)}: ${escapeHtml(formatNumber(result.profit))} (${escapeHtml(formatPercent(result.percent))})</span>
                    <span>${escapeHtml(state.strings.sale_label)}: ${escapeHtml(formatNumber(result.sale))}</span>
                </div>
            </div>`;
        }

        let percent = result.percent;
        if (state.operation === "discount") {
            percent = 100 - result.percent;
        }
        const visible = clamp(Math.abs(percent), 0, 100);
        return `<div class="percentage-bar-wrap">
            <div class="percentage-bar-scale" role="img" aria-label="${escapeHtml(formatPercent(percent))}">
                <div class="percentage-bar-fill" style="width:${visible}%"></div>
            </div>
            <div class="percentage-bar-labels">
                <span>0%</span><strong>${escapeHtml(formatPercent(percent))}</strong><span>100%</span>
            </div>
        </div>`;
    };

    const donutHtml = (result) => {
        const percentage = clamp(Math.abs(result.percent), 0, 100);
        const degrees = percentage * 3.6;
        return `<div class="percentage-donut-wrap">
            <div class="percentage-donut" role="img" aria-label="${escapeHtml(formatPercent(result.percent))}"
                 style="background:conic-gradient(var(--percentage-primary) 0deg ${degrees}deg, #e5eaf0 ${degrees}deg 360deg)">
                <strong>${escapeHtml(formatPercent(result.percent))}</strong>
            </div>
            <div class="percentage-donut-copy">${escapeHtml(formatPercent(percentage))} ${escapeHtml(state.strings.of_total_100)}</div>
        </div>`;
    };

    const gridHtml = (result) => {
        const percentage = clamp(Math.abs(result.percent), 0, 100);
        const full = Math.floor(percentage);
        const fraction = percentage - full;
        let cells = "";
        for (let i = 0; i < 100; i++) {
            if (i < full) {
                cells += `<span class="percentage-grid-cell is-filled"></span>`;
            } else if (i === full && fraction > 0) {
                const degrees = fraction * 360;
                cells += `<span class="percentage-grid-cell is-partial" style="background:conic-gradient(var(--percentage-primary) 0deg ${degrees}deg, #f0f3f7 ${degrees}deg 360deg)"></span>`;
            } else {
                cells += `<span class="percentage-grid-cell"></span>`;
            }
        }
        return `<div class="percentage-grid100" role="img" aria-label="${escapeHtml(formatPercent(result.percent))}">${cells}</div>
            <div class="percentage-grid-caption"><strong>${escapeHtml(formatPercent(result.percent))}</strong> = ${escapeHtml(formatNumber(percentage))} ${escapeHtml(state.strings.out_of_100)}</div>`;
    };

    const comparisonHtml = (result) => {
        const initial = Number(result.initial ?? result.base ?? 0);
        const final = Number(result.final ?? result.answer ?? 0);
        const maximum = Math.max(Math.abs(initial), Math.abs(final), 1);
        const initialWidth = clamp(Math.abs(initial) / maximum * 100, 0, 100);
        const finalWidth = clamp(Math.abs(final) / maximum * 100, 0, 100);

        return `<div class="percentage-comparison">
            <div class="percentage-comparison-row">
                <strong>${escapeHtml(state.strings.original_label)}</strong>
                <div class="percentage-comparison-scale"><span style="width:${initialWidth}%"></span></div>
                <span>${escapeHtml(formatNumber(initial))}</span>
            </div>
            <div class="percentage-comparison-row">
                <strong>${escapeHtml(state.strings.final_label)}</strong>
                <div class="percentage-comparison-scale"><span style="width:${finalWidth}%"></span></div>
                <span>${escapeHtml(formatNumber(final))}</span>
            </div>
            <div class="percentage-comparison-labels"><strong>${escapeHtml(result.primary)}</strong></div>
        </div>`;
    };

    const loadStrings = () => {
        const keys = [
            "calculate", "invalidnumber", "cannotdividebyzero", "result_percentageof", "result_whatpercentage",
            "result_change", "result_increase", "result_discount", "result_margin", "result_markup", "result_profit",
            "explanation_percentageof", "explanation_whatpercentage", "explanation_margin", "of_total_100", "out_of_100",
            "increase_label", "decrease_label", "original_label", "final_label", "cost_label", "sale_label", "profit_label",
        ];
        return Str.get_strings(keys.map((key) => ({key: key, component: "mod_percentage"}))).then((values) => {
            keys.forEach((key, index) => {
                state.strings[key] = values[index];
            });
        });
    };

    const bind = (root) => {
        root.on("click", "[data-action='select-operation']", function() {
            setOperation(root, $(this).data("operation"));
        });

        root.on("click", "[data-action='calculate']", function() {
            calculate(root);
        });

        root.on("keydown", "input", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                calculate(root);
            }
        });

        root.on("click", "[data-action='change-visualization']", function() {
            state.visualization = $(this).data("visualization");
            renderVisual(root, state.visualization);
        });
    };

    const init = (options) => {
        const root = $("[data-region='percentage-calculator']").first();
        if (!root.length) {
            return;
        }

        state.options = {
            visualization: options.visualization || root.data("default-visualization") || "auto",
            decimalplaces: Number(options.decimalplaces ?? root.data("decimal-places") ?? 2),
            showformula: Boolean(options.showformula),
            showexplanation: Boolean(options.showexplanation),
        };
        state.visualization = state.options.visualization;
        state.operation = root.find("[data-action='select-operation']").first().data("operation");
        setOperation(root, state.operation);

        loadStrings().then(() => {
            bind(root);
            calculate(root);
        });
    };

    return {init: init};
});
