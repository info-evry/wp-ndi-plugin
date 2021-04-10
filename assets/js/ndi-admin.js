const wrap = document.getElementsByClassName('wrap')[0];
const {
    ndi_members: _members,
    ndi_teams: _teams
} = ndi_globals;

const csvHeader = ['prenom','nom','mail','niveauBac','equipe','estLeader (0\\1)','ecole (nom exact saisi sur le site)'];

ndi_globals.ndi_school = 'Université d\'Evry';

function getTeamNameById(teamid, teams = _teams) {
    for (const { id, teamName } of _teams) {
        if (teamid === id) {
            return teamName;
        }
    }
}

[...document.getElementsByTagName('table')].forEach( table => {
    const teamId = table.dataset.teamId;
    const { ndi_members: members } = ndi_globals;
    const teamMembers = members.filter( ({ teamid }) => teamid === teamId );
    table.insertAdjacentElement( 'afterend', ndi_ecs({
        t: 'a',
        c: ['button','button-primary'],
        i: [`Export ${getTeamNameById(teamId)} to CSV`],
        a: { value: 'Export' },
        x: {
            addEventListener: ['click', function () {
                const link = document.createElement('a');
                link.setAttribute('href', getCSVFrom( teamMembers ) );
                link.setAttribute('download', `participants_${getTeamNameById(teamId)}.csv`);
                wrap.appendChild(link);
                link.click();
                link.remove();
                return true;
            }, false]
        }
    }));
});

function getCSVFrom ( members = _members ) {
    const { ndi_school: sn } = ndi_globals;
	const lines = [['prenom','nom','mail','niveauBac','equipe','estLeader (0\\1)','ecole (nom exact saisi sur le site)']];
    for (const member of members) {
        const { firstname, lastname, email, baclevel, ismanager, teamid } = member;
        lines.push([firstname, lastname.toUpperCase(), email, baclevel, getTeamNameById(teamid), ismanager, sn]);
    }
    const universalBOM = '\uFEFF';
    const rows = lines.map(e => e.join(';')).join('\n');
    return 'data:text/csv;charset=utf-8,' + encodeURIComponent(universalBOM+rows);
}

function exportCSV(event) {
    if (ndi_globals) {
        const link = document.createElement('a');
        link.setAttribute('href', getCSVFrom( ) );
        link.setAttribute('download', 'participants.csv');
        wrap.appendChild(link);
        link.click();
        link.remove();
        return true;
    }
    return false;
}

const bt = ndi_ecs(
    {
        t: 'a',
        c: ['button','button-primary'],
        i: ['Export All to CSV'],
        a: { value: 'Export' },
        x: {
            addEventListener: ['click', exportCSV, false]
        }
    });
document.getElementById('post-body-content').insertAdjacentElement( 'afterbegin', bt );

bt.insertAdjacentElement('afterend',ndi_ecs({
    c: ['team-part'],
    i: [
        {t:'h2',i:[`Nombre d'Equipes : ${_teams.length}`]},
        {t:'h2',i:[`Nombre de Participants : ${_members.length}`]},
        {t:'hr'},
        {t:'h2',i:['Régimes Alimentaires :']},
        {t: 'ul', i:[
            ...(()=>{
                const diets = new Map();
                _members.forEach( ({fooddiet}) => {
                    if ( fooddiet !== null ) {
                        let count = 1;
                        if ( diets.has( fooddiet ) ) {
                            count += diets.get( fooddiet );
                        }
                        diets.set( fooddiet, count );
                    }
                });
                const arrayOfDiets = [];
                for ( const [ k, v ] of diets.entries() ) {
                    arrayOfDiets.push({ t: 'li', i:[ `${k} : <strong>${v}</strong>` ]});
                }
                return arrayOfDiets;
            })()
        ]}
    ]
}));