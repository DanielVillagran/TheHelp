insert into events( "name", phone, category, logo, effective_date, expiration_date, event_type, modal_type, draw, points_per_draw, points_per_win, points_per_loss, points_per_win_default, points_per_lose_default, players_per_team, description, price_per_game, price_initial, price_deposit, email_notification, email_sms, email_app, electronic_payments, user_id, private_event, sport_id, status, facebook_id, twitter_id, googleplus_id, pinterest_id, prize, contact_name, contact_email, event_type_id, points_per_result, points_per_exactly, points_per_exactly2, points_per_exactly3, points_per_exactly4, points_per_result2, points_per_result3, points_per_result4, resultados_event, got, registry_active, closed, nfl)
SELECT 'MLB 2026', phone, category, logo, effective_date, expiration_date, event_type, modal_type, draw, points_per_draw, points_per_win, points_per_loss, points_per_win_default, points_per_lose_default, players_per_team, description, price_per_game, price_initial, price_deposit, email_notification, email_sms, email_app, electronic_payments, user_id, private_event, sport_id, status, facebook_id, twitter_id, googleplus_id, pinterest_id, prize, contact_name, contact_email, event_type_id, points_per_result, points_per_exactly, points_per_exactly2, points_per_exactly3, points_per_exactly4, points_per_result2, points_per_result3, points_per_result4, resultados_event, got, registry_active, closed, nfl
FROM public.events
where id = 194 

insert into event_sequence( event_id, "sequence", created)
SELECT 198, "sequence", created
FROM public.event_sequence
where event_id =194

insert into "groups" ("name", event_sequence_id)
select  "name", 113
FROM public."groups"
where event_sequence_id =109

insert into team_at_group (group_id, team_id, games, win, lost, draw, lost_default, scored, received, diference, pts, status, "token", "tokenCn"
)
SELECT 384, team_id, games, win, lost, draw, lost_default, scored, received, diference, pts, status, "token", "tokenCn"
FROM public.team_at_group
where group_id =375

insert into matches (event_sequence_id, home_id, visit_id,  status_id,  week_id, initial_date)
select
    113 as event_sequence_id,
    home_team.id as home_team_id,
    away_team.id as away_team_id,
    1,
    768,
    (
        (m."Game Date"::date + m."Local Time"::time) - interval '5 hours'
    ) as game_datetime
    --(m."Game Date"::date + m."Local Time"::time) 
from mlb m
left join lateral (
    select t.id
    from "groups" g
    join team_at_group tag on tag.group_id = g.id
    join teams t on t.id = tag.team_id
    where g.event_sequence_id = 113
      and t."name" ilike '%' || m."Home Team" || '%'
    limit 1
) home_team on true
left join lateral (
    select t.id
    from "groups" g
    join team_at_group tag on tag.group_id = g.id
    join teams t on t.id = tag.team_id
    where g.event_sequence_id = 113
      and t."name" ilike '%' || m."Away Team" || '%'
    limit 1
) away_team on true
order by m."Game Date",m."Local Time";