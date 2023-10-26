import axios from 'axios'

/**
 * For adding players on team form
 */
document
  .querySelectorAll('.custom-add-player-button')
  .forEach(btn => {
    btn.addEventListener('click', addPlayerToCollection)
  })

function addPlayerToCollection (e) {
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass)
  console.log(collectionHolder)
  const item = document.createElement('div')

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    )

  collectionHolder.appendChild(item)

  collectionHolder.dataset.index++

  addPlayerFormDeleteLink(item)
};

/**
 * For removing players from team form
 */
function addPlayerFormDeleteLink (item) {
  const removeFormButton = document.createElement('button')
  removeFormButton.classList.add('btn', 'btn-sm', 'btn-danger', 'ms-auto', 'd-block')
  removeFormButton.innerText = 'Delete this player'

  item.append(removeFormButton)

  removeFormButton.addEventListener('click', (e) => {
    e.preventDefault()
    item.remove()
  })
}

/**
 * Getting data of a team
 */
const getTeamDataUrl = document.getElementsByTagName('body') ? document.getElementsByTagName('body')[0].dataset.getTeamDataUrl : null

async function getTeamData (teamId) {
  let teamdata = 0

  await axios.get(getTeamDataUrl, {
    params: {
      teamId
    }
  }).then(function (response) {
    teamdata = response
  })
  return teamdata
}

/**
 * Setting balance input
 */
function setHTMLBalance () {
  const teamFields = document.querySelectorAll('.custom-operator, .custom-concern')

  if (teamFields.length > 0) {
    teamFields.forEach((teamField) => {
      teamField.addEventListener('change', async function (evt) {
        const teamData = await getTeamData(evt.target.value, getTeamDataUrl)
        const balance = teamData.data.moneyBalance
        evt.target.closest('.custom-select-parent').querySelector('.custom-team-balance').value = balance
      })
    })
  }
}

setHTMLBalance()

/**
 * Fitlering player dropdown list of player
 * according to the concern team selected
 */

document.addEventListener('DOMContentLoaded', function (event) {
  const playerDropdown = document.querySelector('.custom-concern-player')
  const concernTeam = document.querySelector('.custom-concern')

  if (playerDropdown && concernTeam) {
    playerDropdown.innerHTML = '<option>Select a player</option>'

    concernTeam.addEventListener('change', async function (evt) {
      playerDropdown.innerHTML = '<option>Select a player</option>'

      const teamPlayers = await getTeamData(evt.target.value).then((response) => response.data.players)

      teamPlayers.forEach((elem) => {
        playerDropdown.innerHTML += '<option value="' + elem.id + '">' + elem.fullname + '</option>'
      })
    })
  }
})
