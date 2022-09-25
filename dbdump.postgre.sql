--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5 (Debian 14.5-1.pgdg110+1)
-- Dumped by pg_dump version 14.5 (Debian 14.5-1.pgdg110+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: documents; Type: TABLE; Schema: public; Owner: my_app
--

CREATE TABLE public.documents (
    id integer NOT NULL,
    "originalName" text NOT NULL,
    created timestamp without time zone NOT NULL,
    modified timestamp without time zone NOT NULL,
    "relativeFilePath" text NOT NULL,
    status character varying(255) NOT NULL
);


ALTER TABLE public.documents OWNER TO my_app;

--
-- Name: documents_id_seq; Type: SEQUENCE; Schema: public; Owner: my_app
--

CREATE SEQUENCE public.documents_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.documents_id_seq OWNER TO my_app;

--
-- Name: documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: my_app
--

ALTER SEQUENCE public.documents_id_seq OWNED BY public.documents.id;


--
-- Name: phinxlog; Type: TABLE; Schema: public; Owner: my_app
--

CREATE TABLE public.phinxlog (
    version bigint NOT NULL,
    migration_name character varying(100),
    start_time timestamp without time zone,
    end_time timestamp without time zone,
    breakpoint boolean DEFAULT false NOT NULL
);


ALTER TABLE public.phinxlog OWNER TO my_app;

--
-- Name: documents id; Type: DEFAULT; Schema: public; Owner: my_app
--

ALTER TABLE ONLY public.documents ALTER COLUMN id SET DEFAULT nextval('public.documents_id_seq'::regclass);


--
-- Data for Name: documents; Type: TABLE DATA; Schema: public; Owner: my_app
--

COPY public.documents (id, "originalName", created, modified, "relativeFilePath", status) FROM stdin;
\.


--
-- Data for Name: phinxlog; Type: TABLE DATA; Schema: public; Owner: my_app
--

COPY public.phinxlog (version, migration_name, start_time, end_time, breakpoint) FROM stdin;
20220919091355	InitialDocuments	2022-09-25 18:15:04	2022-09-25 18:15:04	f
\.


--
-- Name: documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: my_app
--

SELECT pg_catalog.setval('public.documents_id_seq', 1, false);


--
-- Name: documents documents_pkey; Type: CONSTRAINT; Schema: public; Owner: my_app
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- Name: phinxlog phinxlog_pkey; Type: CONSTRAINT; Schema: public; Owner: my_app
--

ALTER TABLE ONLY public.phinxlog
    ADD CONSTRAINT phinxlog_pkey PRIMARY KEY (version);


--
-- PostgreSQL database dump complete
--

