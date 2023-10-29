import axios from 'axios'

/**
 * To add players on team form
 */
document
  .querySelectorAll('.custom-add-player-button')
  .forEach(btn => {
    btn.addEventListener('click', addPlayerToCollection)
  })

function addPlayerToCollection(e) {
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
function addPlayerFormDeleteLink(item) {
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

async function getTeamData(teamId) {

  let teamdata = 0

  await axios.get(getTeamDataUrl, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    },
    params: {
      teamId
    }
  }).then(function (response) {
    teamdata = response
  })
  return teamdata
}

/**
 * Setting balance input if there
 * is operation form
 */
function setHTMLBalance() {
  const teamFields = document.querySelectorAll('.custom-operator, .custom-concern')
  const tOperator = document.querySelector('.custom-operator')
  const tConcern = document.querySelector('.custom-concern')

  if (teamFields.length > 0) {
    teamFields.forEach((teamField) => {
      teamField.addEventListener('change', async function (evt) {
        const teamData = await getTeamData(evt.target.value, getTeamDataUrl)
        const balance = teamData.data.moneyBalance
        const inputBalance = evt.target.closest('.custom-select-parent').querySelector('.custom-team-balance')
        inputBalance.value = balance

        if (tConcern.value && tOperator.value && tConcern.value === tOperator.value) {
          alert("Same team selection not allowed !")
          tConcern.value = ""
          tConcern.dispatchEvent(new Event('change'));
        }

        inputBalance.dispatchEvent(new Event('change'));

      })
    })
  }
}

/**
 * Fitlering player dropdown list of player
 * according to the concern team selected
 */

function setPlayerDropdown() {
  const playerDropdown = document.querySelector('.custom-concern-player')
  const concernTeam = document.querySelector('.custom-concern')
  const operatorTeam = document.querySelector('.custom-operator')
  const operationSelect = document.querySelector('.custom-operation-type-select')
  const operationTeams = document.querySelectorAll('.custom-operator, .custom-concern')

  if (playerDropdown && concernTeam && operatorTeam && operationSelect) {
    playerDropdown.innerHTML = '<option>Select a player</option>'
    
    operationTeams.forEach((opTeam) => {
      opTeam.addEventListener('change', async function (evt) {
        playerDropdown.innerHTML = '<option>Select a player</option>'

        let playersFrom = null;
        let teamPlayers = null;

        if (evt.target.value && operationSelect.value === 'buy') {
          playersFrom = concernTeam.value ?? null
        }
        else if (evt.target.value && operationSelect.value === 'sell') {
          playersFrom = operatorTeam.value ?? null
        }

        if(playersFrom){
          teamPlayers = await getTeamData(playersFrom).then((response) => response.data.players ?? null)
        }

        if(teamPlayers){
          teamPlayers.forEach((elem) => {
            playerDropdown.innerHTML += '<option value="' + elem.id + '">' + elem.fullname + '</option>'
          })

        }


      })
    })

    operationSelect.addEventListener('change', function(evt){
      
      let operationGet = document.querySelector('.custom-operation-get')
      let operationGetTeam = document.querySelector('.custom-operation-get-team')
      let operationGetNone = document.querySelector('.custom-operation-get-none')
      let operationGetContainer = document.querySelector('.custom-operation-get-container')

      if(evt.target.value){
        operationGet.innerText = evt.target.value
        
        if(evt.target.value === 'sell'){
          operatorTeam.dispatchEvent(new Event('change'))
        }
        else{
          concernTeam.dispatchEvent(new Event('change'))
        }
        
        operationGetContainer.classList.remove('d-none')
        operationGetNone.classList.add('d-none')
        operationGetTeam.innerText = evt.target.value === 'sell' ? 'selected operator' : 'selected concern'
      }
      else{
        operationGetContainer.classList.add('d-none')
        operationGetNone.classList.remove('d-none')
      }

    })

    operationSelect.dispatchEvent(new Event('change'))

  }
}



/** 
 * Handling whether purchaser operator team balance is lower than
 * operation amount if the type is a buy operation
 * 
 * And whether purchaser concern team balance is lower than
 * operation amount if the type is a sell operation from operator team
 */

function validating() {
  const operationSelect = document.querySelector('.custom-operation-type-select');

  if (operationSelect) {
    const form = operationSelect.closest('form');
    const operatorBalance = form.querySelector('.custom-operator-balance')
    const concernBalance = form.querySelector('.custom-concern-balance')
    const operationAmount = form.querySelector('.custom-operation-amount')

    const changingElems = form.querySelectorAll('.custom-concern-balance,.custom-operator-balance,.custom-operation-amount,.custom-operation-type-select')

    const btnSubmit = form.querySelector('button[type="submit"]')
    btnSubmit.classList.add('disabled')

    changingElems.forEach((elem) => {

      elem.addEventListener('change', function (chngEvt) {

        if (operatorBalance.value && concernBalance.value) {
          if (operationSelect.value === 'buy' && parseFloat(operationAmount.value) > parseFloat(operatorBalance.value)) {
            btnSubmit.classList.add('disabled')
            alert("The operator team has lower balance than the amount to purchase !")
          }
          else if (operationSelect.value == 'sell' && parseFloat(operationAmount.value) > parseFloat(concernBalance.value)) {
            btnSubmit.classList.add('disabled')
            alert('The concern team has lower balance than the sold amount !')
          }
          else {
            if (operationSelect.value && operationAmount.value) {
              btnSubmit.classList.remove('disabled')
            }
            else {
              btnSubmit.classList.add('disabled')
            }

          }
        }



      })

    })

  }
}


document.addEventListener('DOMContentLoaded', function () {
  validating()
  setHTMLBalance()
  setPlayerDropdown()
})