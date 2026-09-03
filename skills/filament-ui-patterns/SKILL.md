---
name: filament-ui-patterns
description: >
  Structure Filament UIs with consistent grouping, labeling, density, help
  text, tables, actions, and navigation. Use when creating or editing any
  Filament Resource, Schema, Table, Page, Action, RelationManager, Widget, or
  Infolist — with or without a custom theme such as Vice.
---

# Filament UI Patterns

Follow this skill whenever you build or change Filament UI. Match the host
app's language for user-facing copy. These rules are about structure and
density, not theme CSS.

Before adding a field, column, helper, filter, or action: ask whether it
helps the user identify, compare, or complete a task. If not, omit it.

## File Structure

Resources stay thin. Forms, tables, and custom actions live in dedicated classes.

```
Resources/
  {Model}/
    {Model}Resource.php
    Schemas/{Model}Form.php
    Tables/{Model}sTable.php
    Pages/{Create,Edit,List}{Model}.php
    Actions/SomeCustomAction.php
    RelationManagers/{Relation}RelationManager.php
    Widgets/{Model}SomeWidget.php
```

- Resource `form()` / `table()` only call `SomeForm::configure($schema)` and `SomeTable::configure($table)`.
- Register relation managers with named keys: `'referents' => ReferentsRelationManager::class`.
- When a `$relatedResource` exists, inherit its form and table. Do not duplicate them.

## Form Grouping

### Section is the only root container

Never put bare fields at the schema root. Every form starts with one or more `Section`s.

Always set the header triplet:

```php
Section::make('Dettagli')
    ->description('Classificazione del cliente')
    ->icon(Heroicon::OutlinedDocumentText)
    ->columns(2)
    ->schema([
        //
    ]),
```

- Title: a **noun**, sentence case, app language. Not a verb or a question.
- Description: one short phrase of purpose. Not a question, not a list.
- Icon: `Heroicon::Outlined*` only. See vocabulary below.

### Group for inline grids (no card)

Use `Group` inside a section when fields share a row without a second visual box:

```php
Group::make()
    ->columnSpanFull()
    ->columns(3)
    ->schema([
        Select::make('type')->columnSpan(1),
        Select::make('related_id')->columnSpan(2),
    ]),
```

Children of `Group` always get explicit `->columnSpan(n)`.

### Tabs only for a real conceptual split

Use `Tabs` when the form has two or more distinct areas the user navigates between. Do not use tabs to shorten scroll.

```php
Tabs::make('tabs')
    ->columnSpanFull()
    ->contained(false)
    ->tabs([
        Tab::make('Dettagli')->icon(Heroicon::OutlinedDocumentText)->schema([...]),
        Tab::make('Minuta')->icon(Heroicon::OutlinedSparkles)->schema([...]),
    ]),
```

Tab labels are nouns. Every tab has an icon. `->contained(false)` when the page already provides a card.

### Never use Wizard or Fieldset

`Wizard` is a different UX. `Fieldset` adds noise without meaning.

### Column counts

- Section default: `->columns(2)`.
- `->columns(1)` only for a section of one wide field.
- `->columns(3)` only for three equal-width fields on one row.
- Always set `->columnSpanFull()` on `Textarea`, `RichEditor`, `MarkdownEditor`, `Repeater`, `Tabs`, full-width `Group`, and long titles when the section has more than one column. Never assume full width.

## Section Icon Vocabulary

| Icon | Use for |
|---|---|
| `Heroicon::OutlinedDocumentText` | Details, general info |
| `Heroicon::OutlinedBanknotes` | Money, rates, billing, payment |
| `Heroicon::OutlinedClock` | Dates, deadlines, timing |
| `Heroicon::OutlinedUsers` | People, contacts, referents |
| `Heroicon::OutlinedInformationCircle` | Notes, extras |
| `Heroicon::OutlinedPaperClip` | Attachments, files |
| `Heroicon::OutlinedSparkles` | AI generation, prompts |
| `Heroicon::OutlinedCheckBadge` | Completion, approval |
| `Heroicon::OutlinedLink` | URLs, external references |

Filled/solid icons only on actions that need a stronger mark (e.g. confirm), never on section headers.

## Labels

- Every field has an explicit `->label()`. Never rely on auto-derived names.
- Sentence case, application language.
- Label the **domain concept**, not the column: `paid_on` → `Data di pagamento`.
- Always set `$modelLabel` and `$pluralModelLabel` on the Resource.

## Helper Text

Use `->helperText()` only. Never `->hint()`.

**Add helper text when:**

- The value has a non-obvious side effect (`Il file non sarà più accessibile dopo questa data.`)
- Format or audience is not implied by the input type (e.g. recipient emails)
- A computed default is useful to show (`Default: €120,00`)
- The field drives a downstream computation the user should understand

**Omit helper text when:**

- The label is already enough
- The text only restates the label
- Most fields in the section would need one — then none of them need one

## Placeholders

Use `->placeholder()` only on `Textarea` / `RichEditor` / `MarkdownEditor` and AI prompt fields, and only when an example actually guides input.

Never on name/title `TextInput`, dates, money, `Select`, or toggles.

## Required Fields

- `->required()` on every mandatory field.
- Conditional when it depends on a sibling: `->required(fn (Get $get): bool => $get('passive') === false)`.
- Optional fields stay unmarked — no extra annotation.

## Reactive Fields

- `->live()` on `Select` / `Toggle` that gate visibility or requiredness.
- `->live(onBlur: true)` on text/textarea that drive derived values — never per keystroke.
- `->afterStateUpdated(fn (Set $set) => $set('field', $value))` for derived fields.
- `->visible(fn (Get $get) => ...)` / `->required(fn (Get $get) => ...)` for sibling-driven rules.
- `->visibleOn(Operation::Edit)` for fields that must not appear on create (e.g. `accepted`).

## Other Form Micro-patterns

- `->datalist([...])` on `TextInput` when suggesting existing free-text values. Foreign keys use `Select::make()->relationship()`, never datalist.
- `Hidden::make('client_id')` for context from URL or parent. Prefill via `fillForm()` on the action or page.
- Place AI generate actions **inline** with `Actions::make([...])` between prompt and output. Not in the page header.
- Validation lives on the field (`->required()`, `->email()`, `->numeric()`, `->rules([new SomeRule()])`). Use action-level `Notification` + `$this->halt()` only for checks that need the current record or a side query (duplicates, missing computed amounts).

## Tables

### Column order

**Identity → Amount → Related entity → Date → Status**

1. Primary identifier (name, title, number). Use `->weight(FontWeight::Bold)` when it also has `->description()`.
2. Money via a dedicated money column if the app has one — never format currency by hand on `TextColumn`.
3. Related names (`client.name`, `project.name`).
4. Dates: `->date('d/m/Y')` or `->dateTime('d/m/Y')` to match the app locale.
5. Status last.

```php
TextColumn::make('project.name')
    ->label('Progetto')
    ->weight(FontWeight::Bold)
    ->description(fn ($record) => $record->title)
    ->searchable()
    ->sortable(),
```

### What to show vs omit

Show only columns that help **identify, compare, or triage** without opening the record. Details that only matter while editing belong on the form, not the table.

### Search and sort

- `->searchable()` only on values a user would type to find a row (name, title, number, related name).
- `->sortable()` only on values with a useful order (dates, amounts, names). Not on status prose or descriptions.

### Pagination and default order

- Always `->forcePagination(n)`. Typical: 20; 50 only for dense operational lists.
- Multi-column natural order: `->modifyQueryUsing()` with explicit `orderBy()`.
- Single-column order: `->defaultSort()` is enough.

### Status column (computed)

One `TextColumn` with `->color()` + `->icon()` + `->formatStateUsing()` from the record. Not a raw enum badge when status is derived from several attributes.

### Enum badges

`->badge()` only for discrete enum values. Color and label always come from the enum — never hardcoded in the column:

```php
TextColumn::make('type')
    ->label('Tipo')
    ->badge()
    ->color(fn ($state) => QuotationType::from($state)->color())
    ->formatStateUsing(fn ($state) => QuotationType::from($state)->label()),
```

| Color | Meaning |
|---|---|
| `primary` | Default / main type |
| `success` | Positive, active, complete |
| `warning` | Attention, in progress |
| `info` | Informational type |
| `danger` | Error, blocked, irreversible |
| `gray` | Inactive, low priority, misc |

Booleans: `IconColumn::make(...)->boolean()`, not badges.

### Filters

Do not add filters by default. Add them only for dimensions search cannot cover.

- `SelectFilter` for enum or relationship.
- `Filter` + `->query()` for custom boolean/date logic.
- One filter per real user question, not one per column.

## Actions

| Situation | Pattern |
|---|---|
| Full create/register form from a table | `->slideOver()` |
| Small form (about 2–4 fields) | Default centred modal |
| One-step destructive / irreversible | `->requiresConfirmation()` |

Always set on non-trivial actions: `->label()`, `->icon()`, `->color()`, `->modalHeading()`, `->modalSubmitActionLabel()`.

- Destructive: `->color('danger')`.
- Confirmatory complete/approve: `->color('success')`.
- Secondary header utilities (Reset): `->outlined()`.
- Success: `->successNotificationTitle(...)`. Do not hand-build success notifications.
- Errors in `action()`: `Notification::make()->danger()->title(...)->body($e->getMessage())->send()` then `$this->halt()`.

Icon-only row actions (links, utilities):

```php
Action::make('copy_activity_sheet_url')
    ->label('')
    ->tooltip('Report attività')
    ->icon(Heroicon::OutlinedLink)
    ->url(fn ($record) => $record->activity_sheet_url)
    ->openUrlInNewTab(),
```

`ActionGroup::make([...])` only when a row has **two or more** non-trivial actions. Never wrap a lone Edit.

## Navigation

- `$navigationGroup` only when resources form a real cluster. Do not group everything for visual tidiness.
- `$navigationSort` on every item inside a group.
- `$navigationLabel` only when the model label would confuse in the nav.
- Navigation icons: Outlined heroicons.

## Widgets

- `StatsOverviewWidget` + `Stat::make('Label', $value)` for KPIs.
- `TableWidget` for recent/active lists: `->paginated(false)`, `->heading()`, 2–3 columns max, `->emptyStateHeading()`.
- Set `$columnSpan` and `$sort` on every widget.
- Register on the Page (`getHeaderWidgets()` / `getFooterWidgets()`), not on the Resource.

## Relation Managers

- Use when related records are managed **in the parent context** (phases, line items, attachments, referents).
- Prefer `$relatedResource` so form/table stay single-sourced.
- Minimum header action: `CreateAction::make()`.
- Use a dedicated resource (own nav + pages) when the related model has its own lifecycle or a table too large for a nested manager.

## Infolists / View pages

This project typically has no `ViewRecord` page: create/edit forms are the UI. Add a view + infolist only when users need a read-only record they must not edit.

If you add one:

- Reuse the same section nouns, descriptions, and outlined icons as the form.
- Show fields the table omitted (notes, long text, computed breakdowns).
- Do not duplicate the table; do not turn the infolist into a second edit form.

## Density Checklist

Before finishing a UI change, drop anything that fails these:

- No `->helperText()` unless a side effect, format, or default is non-obvious
- No `->placeholder()` on simple inputs
- No `->hint()`
- No filters unless a user would actually apply them
- No table column that does not help triage
- No `->badge()` on continuous or free-text values
- No `Tabs` just to reduce scroll
- No `Wizard` / `Fieldset`
- No `ActionGroup` around a single action
- No `->dehydrated(false)` on fields that must persist
