const ndi = {
    memberId: 1,
    form: document.getElementById('ndi-form'),
    teamSelector: document.getElementById('ndi-team-selector'),
    teamName: document.getElementById('ndi-team-name'),
    teamDesc: document.getElementById('ndi-team-desc'),
    submit: document.getElementById('ndi-form-validation'),
    memberCount: document.getElementById('ndi-member-count'),
    memberCounter: document.getElementById('ndi-member-counter'),
    team: document.getElementsByClassName('ndi-team'),
    members: document.getElementsByClassName('ndi-member'),
    validMembers: document.getElementsByClassName('ndi-member valid'),
    isManagers: document.getElementsByClassName('ndi-member-ismanager'),
    teamCreationDisabled: false,
    teamSelectionDisabled: false
};



/** Get Online Teams from ndi_globals */
const getOnlineTeams = () => [...ndi_globals.ndi_teams].filter(t => t);
/** Get Online Members from ndi_globals */
const getOnlineMembers = () => [...ndi_globals.ndi_members].filter( t => t );
const capacity = +(ndi_globals.ndi_capacity);
/** Online Teams from ndi_globals */
const $a = getOnlineTeams();
/** Online Members from ndi_globals */
const $b = getOnlineMembers();

const filterMembersByTeamId = ($0, $1) => $1.filter(({ teamid: $2 }) => $2 === $0);
const getManagerInputs = () => [...document.getElementsByClassName('ndi-member-ismanager')].map($0 => getInput($0));
const getSelectedIndex = ($0 = ndi.teamSelector) => $0.options[$0.selectedIndex];
const getSelectedValue = ($0 = getSelectedIndex()) => parseInt($0.value);
const smartValue = ($0) => {
    switch ($0.type) {
        case 'checkbox': { return $0.checked; }
        case 'number': { return parseInt($0.value); }
        default: { return $0.value ? $0.value : null; }
    }
};

const getInput = ($0, $1) => {
    if ($1) {
        return $0.getElementsByClassName($1)[0].getElementsByTagName('input')[0] || null;
    }
    else {
        return $0.getElementsByTagName('input')[0] || null;
    }
};

const getValue = ($0, $1) => smartValue(getInput($0.getElementsByClassName($1)[0]));

/** FUNCTIONS **/
const secureName = ($0) => $0.replace(/[^a-zA-Z0-9\s\\-]/gi, '');
const secureDesc = ($0) => $0.replace(/[^a-zA-Z0-9\s\-\,\!\?\'\.]/gi, '');
const secureInputName = ({ target: $0 }) => { $0.value = secureName($0.value); };
const secureInputDesc = ({ target: $0 }) => { $0.value = secureDesc($0.value); };
const need = ($0) => $0 + '&nbsp;<span class="required">*</span>';
const memberDetail = ({ firstname: $0, lastname: $1 }) => ndi_ecs({ t: 'li', i: [`${$0} ${$1}`] });
const elv = ($0) => {
    if ($0.classList.contains('invalid')) {
        $0.classList.remove('invalid');
    }
    $0.classList.add('valid');
};
const eli = ($0) => {
    if ($0.classList.contains('valid')) {
        $0.classList.remove('valid');
    }
    $0.classList.add('invalid');
};

/** BOOLEAN **/
const isValid = ($0) => $0.validity.valid;
const teamNameExists = ( $0 = smartValue(ndi.teamName) ) => {
    for ( const { teamName: $1 } of $a ) {
        if ( $1.toLowerCase() === $0.toLowerCase() ) { return true; }
    }
    return false;
};
const isFormValid = () => {
    const $0 = aTeamIsSpecified(), $1 = areAllMemberValid(), $2 = hasTeamAManager();
    return $0 && $1 && $2;
};
const isTeamSelectionUsed = () => (getSelectedValue() !== -1) && !isTeamCreationUsed();
const isTeamSelectionDisabled = () => isTeamCreationUsed();
const isTeamCreationUsed = () => !!smartValue(ndi.teamName) && !teamNameExists() && !isTeamSelectionUsed();
const isTeamCreationDisabled = () => isTeamSelectionUsed();
const noTeamSpecified = () => !(isTeamSelectionUsed() || isTeamCreationUsed());
const aTeamIsSpecified = () => isTeamSelectionUsed() || isTeamCreationUsed();
const hasTeamAManager = () => isTeamSelectionUsed() || (isTeamSelectionDisabled() && [...getManagerInputs()].filter(m => m.checked).length === 1);
const isSubmitDisabled = () => ndi.submit.disabled;


const $c = document.getElementById('ndi-valid-teams');
const $x = getOnlineMembers().filter( $0 => $a.some( $1 => $1.teamName === 'Organisation' && $1.id !== $0.teamid ) );
const $d = [...$x].length;

$c.appendChild(ndi_ecs({
	t: 'div', c:['h3'], i: [`${capacity} places disponibles.`]
}));

$c.appendChild(ndi_ecs(
    {
        t: 'ul', i: [
            { t: 'li', i: [`Nombre d'équipes : ${$a.length}`] },
            { t: 'li', i: [`Nombre de participants : ${$d} / ${capacity}`] }
        ]
    }
));

for (const $0 of $a) {
    const { id: $1, teamName: $2, remaining: $3 } = $0;
	const $4 = filterMembersByTeamId($1, $b);
	/** Display Teams */
    $c.append(ndi_ecs({
        t: 'details',
        i: [
            { t: 'summary', i: [$2 === 'Organisation' ? `${$2} - ${$4.length} Organisateurs` : `${$2} - ${$4.length}/5`] },
            { t: 'ul', i: $4.map($0 => memberDetail($0)) }
        ]
    }));
	if ( $2 === 'Organisation' ) {
		ndi.teamSelector.appendChild(ndi_ecs({
    	    t: 'option',
    	    i: [`${$2} - ${$3} places restantes`],
    	    a: { value: $1 },
    	    d: { teamname: $2, remaining: $3 }
	    }));
	}
	else if ( 5 - $4.length ) {
		ndi.teamSelector.appendChild(ndi_ecs({
    	    t: 'option',
    	    i: [`${$2} - ${5 - $4.length} places restantes`],
    	    a: { value: $1 },
    	    d: { teamname: $2, remaining: 5 - $4.length }
	    }));
	}
}


const getFreePlaces = () => {
    if (noTeamSpecified()) {
        return undefined;
    }
    const $0 = ndi.validMembers.length;
    if (isTeamSelectionUsed()) {
        const $1 = parseInt(getSelectedIndex().dataset.remaining);
        return $1 - $0;
    }
    else {
        if (isTeamCreationUsed()) {
            return 5 - $0;
        }
    }
};

const memberExists = ($0, $1) => {
    if ($0 && $1) {
        for (const { firstname: $2, lastname: $3 } of $b) {
            if ($0.toLowerCase() === $2.toLowerCase() && $1.toLowerCase() === $3.toLowerCase()) {
                return true;
            }
        }
    }
    return false;
};

const isMemberValid = ($0) => {
    const $1 = getInput($0, 'ndi-member-firstname'),
        $2 = getInput($0, 'ndi-member-lastname'),
        $3 = getInput($0, 'ndi-member-email'),
        $4 = getInput($0, 'ndi-member-baclevel'),
        $5 = isValid($1),
        $6 = isValid($2),
        $7 = $5 && $6,
        $8 = !memberExists($1.value, $2.value),
        $9 = isValid($3),
        $10 = isValid($4),
        $11 = $7 && $8 && $9 && $10;

    if ($7) {
        if ($8) { elv($1); elv($2); } else { eli($1); eli($2); }
    }
    else {
        if ($5) { elv($1); } else { eli( $1 ); }
        if ($6) { elv($2); } else { eli( $2 ); }
    }
    if ($9) { elv($3); } else { eli($3); }
    if ($10) { elv($4); } else { eli($4); }
    if ($11) { elv($0); } else { eli($0); }
    
    return $11;
};

const areAllMemberValid = ($0 = [...ndi.members]) => $0.length === $0.filter($0 => isMemberValid($0)).length;


const getMemberValues = ($0) => ({
    ismanager: getValue($0, 'ndi-member-ismanager'),
    firstname: getValue($0, 'ndi-member-firstname ') || null,
    lastname: getValue($0, 'ndi-member-lastname') || null,
    email: getValue($0, 'ndi-member-email') || null,
    baclevel: getValue($0, 'ndi-member-baclevel'),
    fooddiet: getValue($0, 'ndi-member-fooddiet') || null
});

const btoaMember = ({ ismanager: $0, firstname: $1, lastname: $2, email: $3, baclevel: $4, fooddiet: $5 }) => ({
    ismanager: $0,
    firstname: $1 ? btoa($1) : null,
    lastname: $2 ? btoa($2) : null,
    email: $3 ? btoa($3) : null,
    baclevel: $4,
    fooddiet: $5 ? btoa($5) : null
});

const exportMemberValues = ($0) => btoaMember(getMemberValues($0));

const createLabel = ($0, $1) => ndi_ecs({
    t: 'label', i: [$0], a: { for: $1 }
});
const createInput = ($0, $1, ...$2) => ndi_ecs({
    t: 'input', id: $0, c: [...$2], a: $1
});
const createInputBlock = ($0, $1, $2, $3, ...$4) => ndi_ecs({
    c: [$0, 'input-block'], i: [createLabel($1, $2), createInput($1, $3, ...$4)]
});
const createMemberPart = (...$1) => ndi_ecs({ c: ['ndi-member-part'], i: [...$1] });


const newMember = () => {
    'use strict';
    const $0 = ndi.memberId++,
        $1 = `ndi-member-${$0}-ismanager`,
        $2 = `ndi-member-${$0}-firstname`,
        $3 = `ndi-member-${$0}-lastname`,
        $4 = `ndi-member-${$0}-email`,
        $5 = `ndi-member-${$0}-baclevel`,
        $6 = `ndi-member-${$0}-fooddiet`,
        $7 = `ndi-member-${$0}-add`,
        $8 = `ndi-member-${$0}-remove`;

    return ndi_ecs({
        c: ['ndi-member'], i: [
            createMemberPart(
                createInputBlock('ndi-member-ismanager', 'Chef d\'équipe', $1, { type: 'checkbox', name: $1 }, 'switch-checkbox')
            ),
            createMemberPart(
                createInputBlock('ndi-member-firstname', need('Prénom'), $2, { type: 'text', name: $2, required: true }),
                createInputBlock('ndi-member-lastname', need('Nom'), $3, { type: 'text', name: $3, required: true })
            ),
            createMemberPart(
                createInputBlock('ndi-member-email', need('Email'), $4, {
                    type: 'email', name: $4, required: true
                }),
                createInputBlock('ndi-member-baclevel', need('Niveau post-bac (année en cours)'), $5, {
                    type: 'number', name: $5, required: true, min: 0, max: 10
                })
            ),
            createMemberPart(
                createInputBlock('ndi-member-fooddiet', 'Régime Alimentaire Particulier', $6, {
                    type: 'text', name: $6, placeholder: 'Végétarien, pas de porc, ...'
                })
            ),
            {
                c: ['ndi-member-actions'], i: [
                    {
                        t: 'button', id: $7, c: ['ndi-add-member'], a: { title: 'Ajouter le membre', disabled: true }, i: ['Ajouter un membre'],
                        x: { addEventListener: ['click', addMember, false] }
                    },
                    {
                        t: 'button', id: $8, c: ['ndi-remove-member'], a: { title: 'Supprimer le membre', disabled: true }, i: ['Supprimer le membre'],
                        x: { addEventListener: ['click', removeMember, false] }
                    }
                ]
            },
        ]
    });
};

const removeMember = ($0) => {
    $0.preventDefault();
    $0.stopPropagation();
    if (ndi.members.length > 1) {
        const $1 = $0.currentTarget.parentElement.parentElement;
        $1.remove();
        ndi.form.dispatchEvent(new Event('input'));
    }
};


const addMember = ($0) => {
    $0.preventDefault();
    $0.stopPropagation();
    const $1 = ndi.members,
        $2 = $1.length,
        $3 = $1.item($2 - 1);
    if (areAllMemberValid()) {
        if ( getFreePlaces() > 0 ) {
            $3.insertAdjacentElement('afterend', newMember() );
            ndi.form.dispatchEvent(new Event('input'));
        }
    }
    else {
        return;
    }
};

const updateCreationState = () => {
    const $0 = isTeamCreationDisabled();
    ndi.teamName.disabled = $0;
    ndi.teamDesc.disabled = $0;
    if ($0) {
        for (const $1 of [...getManagerInputs()]) {
            $1.checked = false;
            $1.disabled = true;
        }
    }
};

const updateSelectionState = () => {
    const $0 = isTeamSelectionDisabled();
    ndi.teamSelector.disabled = $0;
    if ($0 || noTeamSpecified()) {
        for (const $1 of [...getManagerInputs()]) {
            $1.disabled = false;
        }
    }
};

const updateMemberManagement = () => {
    const $1 = ndi.members,
        $2 = $1.length,
        $3 = areAllMemberValid(),
        $4 = getFreePlaces(),
        $5 = aTeamIsSpecified(),
        $6 = $5 && $3 && ( ( $4 - 1 ) >= 0 );
    for ( const $7 of document.getElementsByClassName( 'ndi-add-member ' ) ) {
        $7.disabled = !$6;
    }
    const $8 = $5 && ($2 >= 2);
    for ( const $9 of document.getElementsByClassName( 'ndi-remove-member ' ) ) {
        $9.disabled = !$8;
    }
};

const updateSubmitState = () => {
    const $0 = isFormValid();
    ndi.submit.disabled = !$0;
};

const updateMemberCount = () => {
    const $0 = ndi.memberCounter,
        $1 = 'Aucune équipe sélectionée.';
    if (noTeamSpecified()) {
        $0.innerText = $1;
    }
    else {
		const $2 = data(getSelectedIndex(),'teamname');
		const $3 = getFreePlaces();
		const $4 = $2 === 'Organisation' ? 15 : 5;
		$0.innerHTML = `Membres : ${$4 - $3} / ${$4}<br>Places Restantes : ${$3} / ${$4}`;	
    }
};

const updateIsManagerCheckboxState = () => {
    const $0 = [],
        $1 = [],
        $2 = [...getManagerInputs()];
    if (isTeamSelectionUsed()) {
        for (const $3 of $2) {
            $3.checked = false;
            $3.disabled = true;
        }
    }
    else {
        for (const $4 of $2) {
            if ($4.checked) {
                $0.push($4);
            }
            else {
                $1.push($4);
            }
        }
        const $5 = ($0.length === 1);
        for (const $6 of $1) {
            $6.disabled = $5;
        }
    }
};


function submit($0) {

    $0.stopPropagation();
    $0.preventDefault();

    const $1 = ndi.submit.disabled = isFormValid();

    if (!$1) {
        throw new Error('Submit is disabled.');
    }
    if (!ndi_globals) {
        throw new Error('ndi_globals is undefined.');
    }

    const $2 = {};
    const $3 = [...ndi.members].map($0 => exportMemberValues($0));

    if (isTeamCreationUsed()) {
        const $4 = smartValue(ndi.teamName);
        $2.teamName = $4 ? btoa($4) : null;
        const $5 = smartValue(ndi.teamDesc);
        $2.teamDesc = $5 ? btoa($5) : null;
    }
	if ( isTeamSelectionUsed() ) {
		$2.teamName = btoa(data(getSelectedIndex(),'teamname')) || null;
	    $2.teamId = getSelectedValue();	
	}
	else {
		$2.teamId = -1;
	}

    const {
        ndi_ajax: $6,
        ndi_action: $7,
        ndi_nonce: $8
    } = ndi_globals;

    ndi_fetch({
        type: 'POST',
        url: $6,
        data: {
            ndi_team: encodeURIComponent(JSON.stringify($2)),
            ndi_members: encodeURIComponent(JSON.stringify($3)),
            action: $7,
            _wpnonce: $8,
        }
    }).then($0 => {
        if ($0 && $0 === '1' || $0 === 1) {
            alert('La page va être rechargée automatiquement.\nVérifiez que votre équipe se trouve dans la liste des équipes inscrites.\n\nSi c\'est le cas votre demande d\'inscription a été prise en compte. Nous vous recontacterons rapidement.\n\nSinon contactez nous à asso@info-evry.fr ou inscrivez vous à partir d\'un autre navigateur.');
            window.location.reload(true);
        }
        else {
            alert('Votre demande d\'inscription n\'a pas pu être prise en compte.\n\nContactez nous à contact@adieve.fr pour avoir plus d\'informations ou inscrivez vous à partir d\'un autre navigateur.');
            window.location.reload(true);
        }
    });

}

for (let $0 = 0, $1 = ndi.members.length; $0 < $1; $0 += 1) {
    document.getElementById(`ndi-member-${$0}-add`).addEventListener('click', addMember);
    document.getElementById(`ndi-member-${$0}-remove`).addEventListener('click', removeMember);
}



const $e = [
    ({type}) => console.log( type, Date.now() ),
    updateMemberCount,
    updateCreationState,
    updateSelectionState,
    updateIsManagerCheckboxState,
    updateMemberManagement,
    updateSubmitState
];


for (const $0 of $e) {
    ndi.form.addEventListener('input', $0);
}

ndi.teamName.addEventListener('input', secureInputName);
ndi.teamDesc.addEventListener('input', secureInputDesc);
ndi.form.addEventListener('submit', submit);
