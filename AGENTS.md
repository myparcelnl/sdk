# MyParcel PHP SDK Agent instructions

## Writing

This applies to all text you write in this repository: pull request descriptions, commit
messages, code comments and documentation.

- Write to ASD-STE100 (Simplified Technical English): one idea per sentence, active
  voice, simple tenses, no filler.
- Shorten by cutting whole sentences, never by clipping words.
- No em dashes. Use a comma, a colon or a full stop.
- Use the names the code and the API use. Do not invent labels.
- Write for a reader who does not know the code. Name what happens, not an abstract word.
- Replace a hedge with what you checked: "I did not test this".
- Put code, class names, file paths and commands in backticks.
- English only.

## Pull requests

We squash a pull request by default, see `CONTRIBUTING.md`. The title then becomes the
commit subject and the description the commit message. semantic-release reads both to
make the release and `CHANGELOG.md`.
Every commit message follows the rules for the title and the description too. The
merge method is chosen at merge time, and a rebase puts every commit into the release.

- Name a change by the route the SDK documents for it: a service, factory or model
  outside `src/Client/Generated/` that wraps the generated client, as the README shows.
  Name the generated API client and method only when no such route exists.

### Title

- Use a conventional commit: `type(scope): subject`. `feat`, `fix`, `perf` and `revert`
  appear in the changelog. `build` and `refactor` make a patch release but do not appear
  in the changelog, so a release with only these commits has empty release notes. Use
  `chore`, `ci`, `docs`, `style` or `test` for changes that make no release.
- For a change that integrators see, say what changes in the SDK's public APIs. Use the
  names the SDK uses, also names that this change adds.
- Name the feature an integrator gets, not a generated method or class name. When the
  change has several parts, name the main one. The description lists the rest.
- For a `fix`, start the subject with the verb "fix", for example
  `fix(api): fix isBusiness defaulting to true in capabilities requests`.
- One line, under 72 characters, lower case after the colon, no full stop.

### Description

- Start with one or two sentences on what the change makes possible for an integrator.
- Say why the change is needed, when the diff does not show it: for example a change in
  the API, a bug report, or a limit that integrators ran into.
- Summarise what was added, changed and removed in the public APIs, and what the changes
  make possible or fix for an integrator. Do not walk through the diff file by file.
- Name each new model once, with what it is for. Do not list every property of a model.
  When a property matters, name the PHP method an integrator calls, such as
  `getShopId()`, not the API name `shop_id`.
- When the change breaks integrator code, end the description with a footer:
  `BREAKING CHANGE: <what breaks>. <what an integrator must change>.`
  It makes a major release, so a human reviewer must see it before merging.
  Put nothing below this footer: semantic-release adds all text after it to the changelog.
- Start a line with the words "breaking change", in any case, only in the footer.
  semantic-release reads every line that starts with them as a breaking change.
- On a squash merge, GitHub adds `Co-authored-by:` lines below the description. When the
  description has a footer, remove these lines in the merge dialog. Otherwise they go
  into the release notes.
